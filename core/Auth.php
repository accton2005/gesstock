<?php

namespace Core;

use Core\Database;

class Auth
{
    private static $user = null;
    const SESSION_KEY = 'user_session';
    const MAX_ATTEMPTS = 5;
    const LOCK_DURATION = 900;

    public static function login($email, $password)
    {
        session_start();
        
        $db = Database::getInstance()->getConnection();
        
        $user = $db->prepare(
            "SELECT id, email, password, role, is_active, locked_until FROM users WHERE email = ?"
        )->execute([$email]) ? 
        $db->prepare("SELECT id, email, password, role, is_active, locked_until FROM users WHERE email = ?")->execute([$email])->fetch() : null;

        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            self::recordFailedAttempt($email);
            return false;
        }

        if (!$user['is_active']) {
            return false;
        }

        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            self::recordFailedAttempt($email, $user['id']);
            return false;
        }

        $stmt = $db->prepare(
            "UPDATE users SET last_login = NOW(), login_attempts = 0, locked_until = NULL WHERE id = ?"
        );
        $stmt->execute([$user['id']]);

        self::recordAudit($user['id'], 'login', 'Connexion utilisateur');

        $_SESSION[self::SESSION_KEY] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        self::$user = $_SESSION[self::SESSION_KEY];
        return true;
    }

    public static function logout()
    {
        session_start();
        if (isset($_SESSION[self::SESSION_KEY])) {
            $user = $_SESSION[self::SESSION_KEY];
            self::recordAudit($user['id'], 'logout', 'Déconnexion utilisateur');
            unset($_SESSION[self::SESSION_KEY]);
        }
        session_destroy();
    }

    public static function user()
    {
        session_start();
        if (self::$user === null && isset($_SESSION[self::SESSION_KEY])) {
            self::$user = $_SESSION[self::SESSION_KEY];
        }
        return self::$user;
    }

    public static function check()
    {
        session_start();
        return isset($_SESSION[self::SESSION_KEY]);
    }

    public static function id()
    {
        $user = self::user();
        return $user['id'] ?? null;
    }

    public static function role()
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function can($permission)
    {
        session_start();
        $role = self::role();
        if (!$role) return false;

        $config = require __DIR__ . '/../config/App.php';
        $permissions = $config['PERMISSIONS'][$role] ?? [];

        if (in_array('*', $permissions)) {
            return true;
        }

        return in_array($permission, $permissions);
    }

    public static function authorize($permission, $redirectTo = null)
    {
        if (!self::can($permission)) {
            if ($redirectTo) {
                header("Location: $redirectTo");
                exit;
            }
            http_response_code(403);
            die('Accès refusé');
        }
    }

    private static function recordFailedAttempt($email, $userId = null)
    {
        $db = Database::getInstance()->getConnection();
        
        if ($userId) {
            $stmt = $db->prepare(
                "UPDATE users SET login_attempts = login_attempts + 1 WHERE id = ?"
            );
            $stmt->execute([$userId]);

            $stmt = $db->prepare("SELECT login_attempts FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch();

            if ($result['login_attempts'] >= self::MAX_ATTEMPTS) {
                $lockedUntil = date('Y-m-d H:i:s', time() + self::LOCK_DURATION);
                $stmt = $db->prepare(
                    "UPDATE users SET locked_until = ? WHERE id = ?"
                );
                $stmt->execute([$lockedUntil, $userId]);

                self::recordAudit($userId, 'account_locked', 
                    'Compte verrouillé après ' . self::MAX_ATTEMPTS . ' tentatives');
            }

            self::recordAudit($userId, 'failed_login', 'Tentative de connexion échouée');
        }
    }

    public static function recordAudit($userId, $type, $description = '')
    {
        $db = Database::getInstance()->getConnection();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $stmt = $db->prepare(
            "INSERT INTO securite_logs (user_id, type, description, ip_address, timestamp) 
             VALUES (?, ?, ?, ?, NOW())"
        );
        
        try {
            $stmt->execute([$userId, $type, $description, $ipAddress]);
        } catch (\Exception $e) {
            // Log table might not exist yet
        }
    }

    public static function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    public static function changePassword($userId, $newPassword)
    {
        $db = Database::getInstance()->getConnection();
        $hashedPassword = self::hash($newPassword);

        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $userId]);
    }
}
