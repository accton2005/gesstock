<?php

namespace App\Models;

use Core\Model;
use Core\Database;
use Core\Auth;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    public function createMouvement($data)
    {
        $db = Database::getInstance()->getConnection();
        
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }

    public function getMouvementsJour()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT m.*, a.designation, a.code_interne, u.name
            FROM {$this->table} m
            JOIN articles a ON m.article_id = a.id
            JOIN users u ON m.user_id = u.id
            WHERE DATE(m.date_mouvement) = CURDATE()
            ORDER BY m.date_mouvement DESC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMouvementsArticle($articleId, $limit = 50)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT m.*, u.name, a.designation
            FROM {$this->table} m
            JOIN users u ON m.user_id = u.id
            JOIN articles a ON m.article_id = a.id
            WHERE m.article_id = ?
            ORDER BY m.date_mouvement DESC
            LIMIT ?
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$articleId, $limit]);
        return $stmt->fetchAll();
    }

    public function getMouvementsEnAttente()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT m.*, a.designation, a.code_interne, u.name
            FROM {$this->table} m
            JOIN articles a ON m.article_id = a.id
            JOIN users u ON m.user_id = u.id
            WHERE m.statut = 'en_attente'
            ORDER BY m.created_at ASC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function approveMouvement($id, $userId)
    {
        $db = Database::getInstance()->getConnection();
        
        $db->beginTransaction();
        
        try {
            $stmt = $db->prepare(
                "UPDATE {$this->table} SET statut = 'approuve', approuve_par = ?, date_approbation = NOW() WHERE id = ?"
            );
            $stmt->execute([$userId, $id]);
            
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public function rejectMouvement($id, $motif)
    {
        return $this->update($id, [
            'statut' => 'rejete',
            'justification' => $motif,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getMouvementsFiltrés($type = null, $dateDebut = null, $dateFin = null)
    {
        $db = Database::getInstance()->getConnection();
        $conditions = [];
        $params = [];
        
        if ($type) {
            $conditions[] = "m.type = ?";
            $params[] = $type;
        }
        
        if ($dateDebut) {
            $conditions[] = "DATE(m.date_mouvement) >= ?";
            $params[] = $dateDebut;
        }
        
        if ($dateFin) {
            $conditions[] = "DATE(m.date_mouvement) <= ?";
            $params[] = $dateFin;
        }
        
        $conditions[] = "m.statut = 'approuve'";
        
        $where = implode(' AND ', $conditions);
        
        $sql = "
            SELECT m.*, a.designation, a.code_interne, u.name
            FROM {$this->table} m
            JOIN articles a ON m.article_id = a.id
            JOIN users u ON m.user_id = u.id
            WHERE $where
            ORDER BY m.date_mouvement DESC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
