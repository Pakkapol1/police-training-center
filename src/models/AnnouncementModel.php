<?php
require_once 'BaseModel.php';

class AnnouncementModel extends BaseModel {
    
    public function getLatestAnnouncements($limit = 5) {
        $sql = "SELECT a.*, adm.full_name as author_name 
                FROM announcements a 
                LEFT JOIN admins adm ON a.created_by = adm.id 
                WHERE a.status = 'published' 
                AND (a.start_date IS NULL OR a.start_date <= CURDATE())
                AND (a.end_date IS NULL OR a.end_date >= CURDATE())
                ORDER BY a.priority DESC, a.created_at DESC 
                LIMIT ?";
        $announcements = $this->fetchAll($sql, [$limit]);
        return $this->processLinks($announcements);
    }
    
    public function getPublishedAnnouncements($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT a.*, adm.full_name as author_name 
                FROM announcements a 
                LEFT JOIN admins adm ON a.created_by = adm.id 
                WHERE a.status = 'published' 
                AND (a.start_date IS NULL OR a.start_date <= CURDATE())
                AND (a.end_date IS NULL OR a.end_date >= CURDATE())
                ORDER BY a.priority DESC, a.created_at DESC 
                LIMIT ? OFFSET ?";
        $announcements = $this->fetchAll($sql, [$perPage, $offset]);
        return $this->processLinks($announcements);
    }
    
    public function getAllAnnouncements($page = 1, $perPage = 20, $search = '', $status = 'all', $priority = 'all') {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(a.title LIKE ? OR a.content LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($status !== 'all') {
            $conditions[] = "a.status = ?";
            $params[] = $status;
        }
        
        if ($priority !== 'all') {
            $conditions[] = "a.priority = ?";
            $params[] = $priority;
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT a.*, adm.full_name as author_name 
                FROM announcements a 
                LEFT JOIN admins adm ON a.created_by = adm.id 
                $whereClause 
                ORDER BY a.priority DESC, a.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        $announcements = $this->fetchAll($sql, $params);
        return $this->processLinks($announcements);
    }
    
    public function getTotalAnnouncements($search = '', $status = 'all', $priority = 'all') {
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(title LIKE ? OR content LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($status !== 'all') {
            $conditions[] = "status = ?";
            $params[] = $status;
        }
        
        if ($priority !== 'all') {
            $conditions[] = "priority = ?";
            $params[] = $priority;
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT COUNT(*) as total FROM announcements $whereClause";
        $result = $this->fetch($sql, $params);
        return $result['total'] ?? 0;
    }
    
    public function getAnnouncementById($id) {
        $sql = "SELECT a.*, adm.full_name as author_name 
                FROM announcements a 
                LEFT JOIN admins adm ON a.created_by = adm.id 
                WHERE a.id = ?";
        $announcement = $this->fetch($sql, [$id]);
        if ($announcement) {
            $announcement = $this->processLinks([$announcement])[0];
        }
        return $announcement;
    }
    
    public function createAnnouncement($data) {
        $now = TimeHelper::now();
        $sql = "INSERT INTO announcements (title, content, image, status, priority, start_date, end_date, created_by, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [
            $data['title'],
            $data['content'],
            $data['image'] ?? null,
            $data['status'] ?? 'draft',
            $data['priority'] ?? 'normal',
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['created_by'],
            $now,
            $now
        ]);
    }
    
    public function updateAnnouncement($id, $data) {
        $fields = [];
        $params = [];
        foreach (['title', 'content', 'image', 'status', 'priority', 'start_date', 'end_date'] as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        $params[] = $id;
        $sql = "UPDATE announcements SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->execute($sql, $params);
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE announcements SET status = ? WHERE id = ?";
        return $this->execute($sql, [$status, $id]);
    }
    
    public function deleteAnnouncement($id) {
        $sql = "DELETE FROM announcements WHERE id = ?";
        return $this->execute($sql, [$id]);
    }

    public function addAnnouncementImage($announcementId, $imagePath) {
        $sql = "INSERT INTO announcement_images (announcement_id, image_path) VALUES (?, ?)";
        return $this->execute($sql, [$announcementId, $imagePath]);
    }
    
    public function getAnnouncementImages($announcementId) {
        $sql = "SELECT * FROM announcement_images WHERE announcement_id = ? ORDER BY created_at ASC";
        return $this->fetchAll($sql, [$announcementId]);
    }
    
    public function deleteAnnouncementImage($imageId) {
        $sql = "DELETE FROM announcement_images WHERE id = ?";
        return $this->execute($sql, [$imageId]);
    }
    
    public function getAnnouncementImageById($imageId) {
        $sql = "SELECT * FROM announcement_images WHERE id = ?";
        return $this->fetch($sql, [$imageId]);
    }
    
    public function getLastInsertId() {
        global $pdo;
        return $pdo->lastInsertId();
    }
    
    public function getUrgentAnnouncements() {
        $sql = "SELECT a.*, adm.full_name as author_name 
                FROM announcements a 
                LEFT JOIN admins adm ON a.created_by = adm.id 
                WHERE a.status = 'published' 
                AND a.priority = 'urgent'
                AND (a.start_date IS NULL OR a.start_date <= CURDATE())
                AND (a.end_date IS NULL OR a.end_date >= CURDATE())
                ORDER BY a.created_at DESC 
                LIMIT 5";
        $announcements = $this->fetchAll($sql);
        return $this->processLinks($announcements);
    }
    
    /**
     * ประมวลผลลิงก์ในเนื้อหา
     */
    private function processLinks($announcements) {
        foreach ($announcements as &$announcement) {
            if (isset($announcement['content'])) {
                $announcement['content'] = $this->processContentLinks($announcement['content']);
            }
        }
        return $announcements;
    }
    
    /**
     * แปลงลิงก์ในเนื้อหาให้เป็น HTML
     */
    private function processContentLinks($content) {
        // แปลง URL เป็นลิงก์
        $content = preg_replace(
            '/(https?:\/\/[^\s]+)/i',
            '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-primary">$1</a>',
            $content
        );
        
        // แปลง [text](url) เป็นลิงก์
        $content = preg_replace(
            '/\[([^\]]+)\]\(([^)]+)\)/i',
            '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-primary">$1</a>',
            $content
        );
        
        // แปลง **text** เป็น bold
        $content = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $content);
        
        // แปลง *text* เป็น italic
        $content = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $content);
        
        // แปลง `text` เป็น code
        $content = preg_replace('/`([^`]+)`/', '<code class="bg-light px-1 rounded">$1</code>', $content);
        
        return $content;
    }
} 