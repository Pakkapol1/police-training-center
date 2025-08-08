<?php
require_once __DIR__ . '/BaseModel.php';

class ActivityLogModel extends BaseModel {
    private $table = 'activity_log';

    /**
     * เพิ่มกิจกรรมใหม่
     */
    public function logActivity($data) {
        $sql = "INSERT INTO {$this->table} (
            action_type, module, description, user_id, user_name, 
            ip_address, user_agent, related_id, related_table
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->execute($sql, [
            $data['action_type'],
            $data['module'],
            $data['description'],
            $data['user_id'] ?? null,
            $data['user_name'] ?? null,
            $data['ip_address'] ?? $this->getClientIP(),
            $data['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? null,
            $data['related_id'] ?? null,
            $data['related_table'] ?? null
        ]);
    }

    /**
     * ดึงกิจกรรมล่าสุด
     */
    public function getRecentActivities($limit = 10) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?";
        return $this->fetchAll($sql, [$limit]);
    }

    /**
     * ดึงกิจกรรมตามโมดูล
     */
    public function getActivitiesByModule($module, $limit = 10) {
        $sql = "SELECT * FROM {$this->table} WHERE module = ? ORDER BY created_at DESC LIMIT ?";
        return $this->fetchAll($sql, [$module, $limit]);
    }

    /**
     * ดึงกิจกรรมตามผู้ใช้
     */
    public function getActivitiesByUser($userId, $limit = 10) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        return $this->fetchAll($sql, [$userId, $limit]);
    }

    /**
     * ดึงกิจกรรมตามช่วงเวลา
     */
    public function getActivitiesByDateRange($startDate, $endDate, $limit = 50) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE DATE(created_at) BETWEEN ? AND ? 
                ORDER BY created_at DESC LIMIT ?";
        return $this->fetchAll($sql, [$startDate, $endDate, $limit]);
    }

    /**
     * นับจำนวนกิจกรรมทั้งหมด
     */
    public function getTotalActivities() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * นับจำนวนกิจกรรมตามโมดูล
     */
    public function getActivityCountByModule() {
        $sql = "SELECT module, COUNT(*) as count 
                FROM {$this->table} 
                GROUP BY module 
                ORDER BY count DESC";
        return $this->fetchAll($sql);
    }

    /**
     * นับจำนวนกิจกรรมตามประเภท
     */
    public function getActivityCountByType() {
        $sql = "SELECT action_type, COUNT(*) as count 
                FROM {$this->table} 
                GROUP BY action_type 
                ORDER BY count DESC";
        return $this->fetchAll($sql);
    }

    /**
     * ดึงสถิติกิจกรรมรายวัน (7 วันล่าสุด)
     */
    public function getDailyActivityStats($days = 7) {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as total_activities,
                    COUNT(DISTINCT user_id) as unique_users,
                    COUNT(CASE WHEN action_type = 'create' THEN 1 END) as creates,
                    COUNT(CASE WHEN action_type = 'update' THEN 1 END) as updates,
                    COUNT(CASE WHEN action_type = 'delete' THEN 1 END) as deletes
                FROM {$this->table} 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        return $this->fetchAll($sql, [$days]);
    }

    /**
     * ลบกิจกรรมเก่า (มากกว่า 90 วัน)
     */
    public function cleanOldActivities($days = 90) {
        $sql = "DELETE FROM {$this->table} WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        return $this->execute($sql, [$days]);
    }

    /**
     * ดึง IP Address ของผู้ใช้
     */
    private function getClientIP() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * สร้างข้อความอธิบายกิจกรรม
     */
    public static function createDescription($actionType, $module, $details = []) {
        $descriptions = [
            'news' => [
                'create' => 'เพิ่มข่าวสารใหม่: ' . ($details['title'] ?? ''),
                'update' => 'แก้ไขข่าวสาร: ' . ($details['title'] ?? ''),
                'delete' => 'ลบข่าวสาร: ' . ($details['title'] ?? '')
            ],

            'commanders' => [
                'create' => 'เพิ่มผู้บังคับบัญชาใหม่: ' . ($details['full_name'] ?? ''),
                'update' => 'แก้ไขข้อมูลผู้บังคับบัญชา: ' . ($details['full_name'] ?? ''),
                'delete' => 'ลบผู้บังคับบัญชา: ' . ($details['full_name'] ?? '')
            ],
            'documents' => [
                'create' => 'อัปโหลดเอกสารใหม่: ' . ($details['title'] ?? ''),
                'update' => 'แก้ไขเอกสาร: ' . ($details['title'] ?? ''),
                'delete' => 'ลบเอกสาร: ' . ($details['title'] ?? ''),
                'download' => 'ดาวน์โหลดเอกสาร: ' . ($details['title'] ?? '')
            ],

            'auth' => [
                'login' => 'เข้าสู่ระบบ',
                'logout' => 'ออกจากระบบ'
            ],
            'splash' => [
                'update' => 'อัปเดตการตั้งค่า Splash Page'
            ],
            'slides' => [
                'create' => 'เพิ่มสไลด์ใหม่: ' . ($details['title'] ?? ''),
                'update' => 'แก้ไขสไลด์: ' . ($details['title'] ?? ''),
                'delete' => 'ลบสไลด์: ' . ($details['title'] ?? '')
            ]

        ];

        return $descriptions[$module][$actionType] ?? "{$actionType} ใน {$module}";
    }

    /**
     * ดึงไอคอนสำหรับกิจกรรม
     */
    public static function getActivityIcon($actionType, $module) {
        $icons = [
            'create' => 'fas fa-plus',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash',
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'upload' => 'fas fa-upload',
            'download' => 'fas fa-download',
            'approve' => 'fas fa-check',
            'reject' => 'fas fa-times'
        ];

        return $icons[$actionType] ?? 'fas fa-info-circle';
    }

    /**
     * ดึงสีสำหรับกิจกรรม
     */
    public static function getActivityColor($actionType, $module) {
        $colors = [
            'create' => 'success',
            'update' => 'info',
            'delete' => 'danger',
            'login' => 'primary',
            'logout' => 'secondary',
            'upload' => 'success',
            'download' => 'info',
            'approve' => 'success',
            'reject' => 'danger'
        ];

        return $colors[$actionType] ?? 'secondary';
    }

    /**
     * ดึงกิจกรรมพร้อม filters
     */
    public function getActivitiesWithFilters($filters, $page = 1, $perPage = 20) {
        $conditions = [];
        $params = [];
        
        if (!empty($filters['module'])) {
            $conditions[] = "module = ?";
            $params[] = $filters['module'];
        }
        
        if (!empty($filters['action_type'])) {
            $conditions[] = "action_type = ?";
            $params[] = $filters['action_type'];
        }
        
        if (!empty($filters['date_from'])) {
            $conditions[] = "DATE(created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $conditions[] = "DATE(created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        
        if (!empty($filters['search'])) {
            $conditions[] = "(description LIKE ? OR user_name LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        return $this->fetchAll($sql, $params);
    }

    /**
     * นับจำนวนกิจกรรมพร้อม filters
     */
    public function getTotalActivitiesWithFilters($filters) {
        $conditions = [];
        $params = [];
        
        if (!empty($filters['module'])) {
            $conditions[] = "module = ?";
            $params[] = $filters['module'];
        }
        
        if (!empty($filters['action_type'])) {
            $conditions[] = "action_type = ?";
            $params[] = $filters['action_type'];
        }
        
        if (!empty($filters['date_from'])) {
            $conditions[] = "DATE(created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $conditions[] = "DATE(created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        
        if (!empty($filters['search'])) {
            $conditions[] = "(description LIKE ? OR user_name LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $result = $this->fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * นับจำนวนผู้ใช้ที่ไม่ซ้ำกัน
     */
    public function getUniqueUsersCount() {
        $sql = "SELECT COUNT(DISTINCT user_id) as total FROM {$this->table} WHERE user_id IS NOT NULL";
        $result = $this->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * นับจำนวนกิจกรรมวันนี้
     */
    public function getTodayActivitiesCount() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(created_at) = CURDATE()";
        $result = $this->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * นับจำนวนกิจกรรมสัปดาห์นี้
     */
    public function getThisWeekActivitiesCount() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE YEARWEEK(created_at) = YEARWEEK(NOW())";
        $result = $this->fetch($sql);
        return $result['total'] ?? 0;
    }
} 