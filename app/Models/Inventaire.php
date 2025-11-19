<?php

namespace App\Models;

use Core\Model;
use Core\Database;
use Core\Auth;

class Inventaire extends Model
{
    protected $table = 'inventaires';

    public function generateNumero()
    {
        $date = date('Y-m-d');
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM inventaires WHERE DATE(created_at) = ?");
        $stmt->execute([$date]);
        $result = $stmt->fetch();
        $count = ($result['count'] ?? 0) + 1;
        return 'INV-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function createInventaire($data)
    {
        $db = Database::getInstance()->getConnection();
        $data['numero_inventaire'] = $this->generateNumero();
        $data['responsable'] = $data['responsable'] ?? Auth::id();
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }

    public function getDetailsArticles($inventaireId)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT di.*, a.designation, a.code_interne
            FROM details_inventaire di
            JOIN articles a ON di.article_id = a.id
            WHERE di.inventaire_id = ?
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$inventaireId]);
        return $stmt->fetchAll();
    }

    public function addArticleToInventaire($inventaireId, $articleId, $quantiteAttendue)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            INSERT INTO details_inventaire (inventaire_id, article_id, quantite_attendue)
            VALUES (?, ?, ?)
        ");
        
        return $stmt->execute([$inventaireId, $articleId, $quantiteAttendue]);
    }

    public function recordComptage($detailId, $quantiteComptee, $observation = '')
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT quantite_attendue FROM details_inventaire WHERE id = ?
        ");
        $stmt->execute([$detailId]);
        $row = $stmt->fetch();
        
        $difference = $quantiteComptee - ($row['quantite_attendue'] ?? 0);
        
        $stmt = $db->prepare("
            UPDATE details_inventaire 
            SET quantite_comptee = ?, difference = ?, observation = ?, verifiee = 1
            WHERE id = ?
        ");
        
        return $stmt->execute([$quantiteComptee, $difference, $observation, $detailId]);
    }

    public function closeInventaire($inventaireId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            UPDATE {$this->table}
            SET statut = 'termine', date_fin = NOW()
            WHERE id = ?
        ");
        
        return $stmt->execute([$inventaireId]);
    }

    public function getEcartsInventaire($inventaireId)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT di.*, a.designation, a.code_interne
            FROM details_inventaire di
            JOIN articles a ON di.article_id = a.id
            WHERE di.inventaire_id = ? AND di.difference != 0
            ORDER BY ABS(di.difference) DESC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$inventaireId]);
        return $stmt->fetchAll();
    }
}
