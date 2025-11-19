<?php

namespace App\Services;

use Core\Database;
use Core\Auth;

class AuditService
{
    public static function log($tableName, $recordId, $action, $oldValues = [], $newValues = [])
    {
        $db = Database::getInstance()->getConnection();
        
        $userId = Auth::id();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $stmt = $db->prepare("
            INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent, timestamp)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        try {
            return $stmt->execute([
                $userId,
                $action,
                $tableName,
                $recordId,
                json_encode($oldValues),
                json_encode($newValues),
                $ipAddress,
                $userAgent
            ]);
        } catch (\Exception $e) {
            error_log("Audit logging error: " . $e->getMessage());
            return false;
        }
    }

    public static function getHistory($tableName = null, $recordId = null, $limit = 100)
    {
        $db = Database::getInstance()->getConnection();
        $conditions = [];
        $params = [];
        
        if ($tableName) {
            $conditions[] = "table_name = ?";
            $params[] = $tableName;
        }
        
        if ($recordId) {
            $conditions[] = "record_id = ?";
            $params[] = $recordId;
        }
        
        $where = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $sql = "
            SELECT al.*, u.name as user_name, u.email
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            $where
            ORDER BY al.timestamp DESC
            LIMIT $limit
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function cleanup()
    {
        $db = Database::getInstance()->getConnection();
        $config = require __DIR__ . '/../../config/App.php';
        $retention = $config['LOG_RETENTION_DAYS'] ?? 2555;
        
        $stmt = $db->prepare("
            DELETE FROM audit_logs 
            WHERE timestamp < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        
        return $stmt->execute([$retention]);
    }
}
