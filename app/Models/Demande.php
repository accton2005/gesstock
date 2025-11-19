<?php

namespace App\Models;

use Core\Model;
use Core\Database;
use Core\Auth;

class Demande extends Model
{
    protected $table = 'demandes';

    public function createDemande($data)
    {
        $db = Database::getInstance()->getConnection();
        
        $data['utilisateur_demandeur'] = $data['utilisateur_demandeur'] ?? Auth::id();
        $data['numero_demande'] = $this->generateNumero();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }

    public function generateNumero()
    {
        $db = Database::getInstance()->getConnection();
        $date = date('Y-m-d');
        $stmt = $db->prepare("
            SELECT COUNT(*) as count FROM demandes WHERE DATE(created_at) = ?
        ");
        $stmt->execute([$date]);
        $result = $stmt->fetch();
        $count = ($result['count'] ?? 0) + 1;
        return 'DEM-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function getDemandesEnAttente()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT d.*, u.name as demandeur, u2.name as valideur
            FROM {$this->table} d
            JOIN users u ON d.utilisateur_demandeur = u.id
            LEFT JOIN users u2 ON d.validee_par = u2.id
            WHERE d.statut IN ('soumise', 'en_attente')
            ORDER BY d.date_demande DESC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDemandesUtilisateur($userId)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT d.*, u.name as valideur, COUNT(dd.id) as articles_count
            FROM {$this->table} d
            LEFT JOIN users u ON d.validee_par = u.id
            LEFT JOIN details_demande dd ON d.id = dd.demande_id
            WHERE d.utilisateur_demandeur = ?
            GROUP BY d.id
            ORDER BY d.created_at DESC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function validateDemande($id, $userId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare(
            "UPDATE {$this->table} SET statut = 'validee', validee_par = ?, date_validation = NOW() WHERE id = ?"
        );
        return $stmt->execute([$userId, $id]);
    }

    public function prepareDemande($id, $userId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare(
            "UPDATE {$this->table} SET statut = 'preparee', distributeur = ? WHERE id = ?"
        );
        return $stmt->execute([$userId, $id]);
    }

    public function distributeDemande($id)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare(
            "UPDATE {$this->table} SET statut = 'distribuee', date_distribution = NOW() WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    public function rejectDemande($id, $motif, $userId)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare(
            "UPDATE {$this->table} SET statut = 'rejetee', raison_rejet = ?, validee_par = ?, date_validation = NOW() WHERE id = ?"
        );
        return $stmt->execute([$motif, $userId, $id]);
    }

    public function getDetailsArticles($demandeId)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT dd.*, a.designation, a.code_interne
            FROM details_demande dd
            JOIN articles a ON dd.article_id = a.id
            WHERE dd.demande_id = ?
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$demandeId]);
        return $stmt->fetchAll();
    }
}
