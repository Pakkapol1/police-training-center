<?php
require_once __DIR__ . '/../helpers/TimeHelper.php';

class BaseModel {
    protected $db;
    
    public function __construct() {
        global $pdo;
        $this->db = $pdo;
        
        // ตั้งค่า timezone ทุกครั้งที่สร้าง instance
        TimeHelper::setThaiTimezone();
    }
    
    protected function execute($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                throw new \PDOException($errorInfo[2] ?? 'Unknown SQL error');
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw $e;
        }
    }
    
    protected function fetch($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    
    protected function fetchAll($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
}
