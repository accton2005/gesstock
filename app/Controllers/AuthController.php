<?php

namespace App\Controllers;

use Core\Auth;
use App\Models\User;

class AuthController extends BaseController
{
    public function loginForm()
    {
        if (Auth::check()) {
            header('Location: /dashboard');
            exit;
        }
        
        $this->view('auth.login', [
            'title' => 'Connexion - GES Stock'
        ]);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (Auth::login($email, $password)) {
            $this->redirect('/dashboard', 'Connexion réussie');
        } else {
            $_SESSION['error'] = 'Email ou mot de passe incorrect';
            $this->redirect('/login');
        }
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect('/login', 'Vous avez été déconnecté');
    }

    public function changePasswordForm()
    {
        $this->requireAuth();
        
        $userModel = new User();
        $user = $userModel->find(Auth::id());
        
        $this->view('auth.change-password', [
            'title' => 'Changer le mot de passe',
            'user' => $user
        ]);
    }

    public function changePassword()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Méthode non autorisée');
        }
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        
        if (strlen($newPassword) < 8) {
            $errors['new_password'] = 'Le mot de passe doit contenir au moins 8 caractères';
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Les mots de passe ne correspondent pas';
        }
        
        $userModel = new User();
        $user = $userModel->find(Auth::id());
        
        if (!password_verify($currentPassword, $user['password'])) {
            $errors['current_password'] = 'Le mot de passe actuel est incorrect';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/change-password');
        }
        
        if (Auth::changePassword(Auth::id(), $newPassword)) {
            $this->redirect('/dashboard', 'Mot de passe changé avec succès');
        } else {
            $this->redirect('/change-password', 'Erreur lors du changement de mot de passe');
        }
    }
}
