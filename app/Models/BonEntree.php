<?php

namespace App\Models;

use Core\Model;
use Core\Database;
use Core\Auth;

class BonEntree extends Model
{
    protected $table = 'bons_entree';

    public function generateNumero()
    {
        $db = Database::getInstance()->getConnection();
        $date = date('Y-m-d');
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM bons_entree WHERE DATE(created_at) = ?");
        $stmt->execute([$date]);
        $result = $stmt->fetch();
        $count = ($result['count'] ?? 0) + 1;
        return 'BON-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function createBon($data)
    {
        $db = Database::getInstance()->getConnection();
        $data['numero_bon'] = $this->generateNumero();
        $data['created_by'] = $data['created_by'] ?? Auth::id();
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }

    public function getBonsEnAttente()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT b.*, f.nom as fournisseur, COUNT(dd.id) as articles_count
            FROM {$this->table} b
            JOIN fournisseurs f ON b.fournisseur_id = f.id
            LEFT JOIN details_bon_entree dd ON b.id = dd.bon_id
            WHERE b.statut NOT IN ('livree', 'annulee')
            GROUP BY b.id
            ORDER BY b.date_livraison_prevue ASC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDetailsBon($bonId)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT dd.*, a.designation, a.code_interne
            FROM details_bon_entree dd
            JOIN articles a ON dd.article_id = a.id
            WHERE dd.bon_id = ?
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$bonId]);
        return $stmt->fetchAll();
    }

    public function receiveDelivery($bonId, $details = [])
    {
        $db = Database::getInstance()->getConnection();
        
        $db->beginTransaction();
        
        try {
            $stmt = $db->prepare("
                UPDATE {$this->table} 
                SET statut = 'livree', date_livraison_relle = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$bonId]);
            
            foreach ($details as $detail) {
                $stmt = $db->prepare("
                    UPDATE details_bon_entree 
                    SET quantite_livree = ?
                    WHERE id = ?
                ");
                $stmt->execute([$detail['quantite'], $detail['id']]);
                
                $stmt = $db->prepare("SELECT article_id, quantite_livree FROM details_bon_entree WHERE id = ?");
                $stmt->execute([$detail['id']]);
                $row = $stmt->fetch();
                
                $mouvement = new MouvementStock();
                $mouvement->createMouvement([
                    'article_id' => $row['article_id'],
                    'type' => 'entree',
                    'quantite' => $row['quantite_livree'],
                    'reference_document' => 'BON-' . $bonId,
                    'justification' => 'Livraison fournisseur',
                    'statut' => 'approuve',
                    'approuve_par' => Auth::id(),
                    'date_approbation' => date('Y-m-d H:i:s')
                ]);
            }
            
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
