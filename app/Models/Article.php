<?php

namespace App\Models;

use Core\Model;
use Core\Database;

class Article extends Model
{
    protected $table = 'articles';

    public function getWithStock()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT a.*,
                   COALESCE(SUM(CASE WHEN type='entree' AND statut='approuve' THEN quantite ELSE 0 END) -
                   SUM(CASE WHEN type='sortie' AND statut='approuve' THEN quantite ELSE 0 END), 0) as stock_actuel
            FROM {$this->table} a
            LEFT JOIN mouvements_stock ms ON a.id = ms.article_id
            WHERE a.actif = 1 AND a.deleted_at IS NULL
            GROUP BY a.id
            ORDER BY a.designation
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStockActuel($articleId)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT COALESCE(SUM(CASE WHEN type='entree' THEN quantite ELSE 0 END) -
                   SUM(CASE WHEN type='sortie' THEN quantite ELSE 0 END), 0) as stock
            FROM mouvements_stock
            WHERE article_id = ? AND statut = 'approuve'
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$articleId]);
        $result = $stmt->fetch();
        return $result['stock'] ?? 0;
    }

    public function getArticlesEnAlerte()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT a.*,
                   COALESCE(SUM(CASE WHEN ms.type='entree' THEN ms.quantite ELSE 0 END) -
                   SUM(CASE WHEN ms.type='sortie' THEN ms.quantite ELSE 0 END), 0) as stock_actuel
            FROM {$this->table} a
            LEFT JOIN mouvements_stock ms ON a.id = ms.article_id AND ms.statut = 'approuve'
            WHERE a.actif = 1 AND a.deleted_at IS NULL
            GROUP BY a.id
            HAVING stock_actuel <= a.stock_min
            ORDER BY stock_actuel ASC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search($query)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT * FROM {$this->table}
            WHERE actif = 1 AND deleted_at IS NULL
            AND (code_interne LIKE ? OR designation LIKE ? OR description LIKE ?)
            ORDER BY designation
            LIMIT 20
        ";
        
        $search = "%$query%";
        $stmt = $db->prepare($sql);
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll();
    }

    public function archive($id, $motif)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            UPDATE {$this->table}
            SET actif = 0, date_archivage = NOW(), motif_archivage = ?
            WHERE id = ?
        ";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([$motif, $id]);
    }
}
