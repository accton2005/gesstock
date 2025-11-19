<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\AuditService;
use Core\Auth;

class AdminController extends BaseController
{
    public function users()
    {
        Auth::authorize('*');
        
        $userModel = new User();
        $users = $userModel->getActifs();
        
        $this->view('admin.users', [
            'title' => 'Gestion des Utilisateurs',
            'users' => $users
        ]);
    }

    public function logs()
    {
        Auth::authorize('*');
        
        $logs = AuditService::getHistory(null, null, 500);
        
        $this->view('admin.logs', [
            'title' => 'Journalisation',
            'logs' => $logs
        ]);
    }

    public function createUser()
    {
        Auth::authorize('*');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $db = \Core\Database::getInstance()->getConnection();
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => Auth::hash($_POST['password'] ?? ''),
            'role' => $_POST['role'] ?? 'consultateur',
            'department' => $_POST['department'] ?? '',
            'is_active' => 1
        ];
        
        $stmt = $db->prepare("
            INSERT INTO users (name, email, password, role, department, is_active)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['department'],
            $data['is_active']
        ])) {
            $id = $db->lastInsertId();
            AuditService::log('users', $id, 'CREATE', [], $data);
            $this->redirect('/admin/users', 'Utilisateur créé');
        }
    }
}
