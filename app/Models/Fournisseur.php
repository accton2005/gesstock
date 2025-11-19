<?php

namespace App\Models;

use Core\Model;

class Fournisseur extends Model
{
    protected $table = 'fournisseurs';

    public function getActifs()
    {
        return $this->where(['actif' => 1]);
    }

    public function search($query)
    {
        $db = \Core\Database::getInstance()->getConnection();
        $sql = "
            SELECT * FROM {$this->table}
            WHERE actif = 1 AND (nom LIKE ? OR email LIKE ? OR telephone LIKE ?)
            ORDER BY nom
        ";
        
        $search = "%$query%";
        $stmt = $db->prepare($sql);
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll();
    }
}
