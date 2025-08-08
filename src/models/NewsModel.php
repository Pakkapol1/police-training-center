<?php
require_once 'BaseModel.php';

class NewsModel extends BaseModel {
    
    public function getLatestNews($limit = 5) {
        $sql = "SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT ?";
        return $this->fetchAll($sql, [$limit]);
    }
    
    public function getPublishedNews($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT ? OFFSET ?";
        return $this->fetchAll($sql, [$perPage, $offset]);
    }
    
    public function getAllNews($page = 1, $perPage = 20, $search = '', $status = 'all') {
        $offset = ($page - 1) * $perPage;
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
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT n.*, n.category, a.full_name as author_name FROM news n 
                LEFT JOIN admins a ON n.created_by = a.id 
                $whereClause ORDER BY n.created_at DESC LIMIT ? OFFSET ?";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        return $this->fetchAll($sql, $params);
    }
    
    public function getTotalNews($search = '', $status = 'all') {
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
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT COUNT(*) as total FROM news $whereClause";
        $result = $this->fetch($sql, $params);
        return $result['total'] ?? 0;
    }
    
    public function getNewsById($id) {
        $sql = "SELECT n.*, n.category, a.full_name as author_name FROM news n 
                LEFT JOIN admins a ON n.created_by = a.id WHERE n.id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function createNews($data) {
        $now = TimeHelper::now();
        $sql = "INSERT INTO news (title, content, image, status, created_by, category, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [
            $data['title'],
            $data['content'],
            $data['image'] ?? null,
            $data['status'] ?? 'draft',
            $data['created_by'],
            $data['category'] ?? null,
            $now,
            $now
        ]);
    }
    
    public function updateNews($id, $data) {
        $fields = [];
        $params = [];
        foreach (['title', 'content', 'image', 'status', 'category'] as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        $params[] = $id;
        $sql = "UPDATE news SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->execute($sql, $params);
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE news SET status = ? WHERE id = ?";
        return $this->execute($sql, [$status, $id]);
    }
    
    public function deleteNews($id) {
        $sql = "DELETE FROM news WHERE id = ?";
        return $this->execute($sql, [$id]);
    }

    public function addNewsImage($newsId, $imagePath) {
        $sql = "INSERT INTO news_images (news_id, image_path) VALUES (?, ?)";
        return $this->execute($sql, [$newsId, $imagePath]);
    }
    public function getNewsImages($newsId) {
        $sql = "SELECT * FROM news_images WHERE news_id = ?";
        return $this->fetchAll($sql, [$newsId]);
    }
    
    public function deleteNewsImage($imageId) {
        $sql = "DELETE FROM news_images WHERE id = ?";
        return $this->execute($sql, [$imageId]);
    }
    
    public function getNewsImageById($imageId) {
        $sql = "SELECT * FROM news_images WHERE id = ?";
        return $this->fetch($sql, [$imageId]);
    }
    
    public function getLastInsertId() {
        global $pdo;
        return $pdo->lastInsertId();
    }
}
