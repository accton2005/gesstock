<?php

namespace App\Models;

use Core\Model;
use Core\Database;

class User extends Model
{
    protected $table = 'users';

    public function getActifs()
    {
        return $this->where(['is_active' => 1]);
    }

    public function getByRole($role)
    {
        return $this->where(['role' => $role, 'is_active' => 1]);
    }

    public function getChefs()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "
            SELECT * FROM {$this->table}
            WHERE role IN ('chef_service', 'admin') AND is_active = 1
            ORDER BY name
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getActiveCount()
    {
        return $this->count(['is_active' => 1]);
    }
}
