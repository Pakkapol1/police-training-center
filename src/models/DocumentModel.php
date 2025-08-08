<?php
require_once 'BaseModel.php';

class DocumentModel extends BaseModel {
    
    public function getDocuments($category = 'all') {
        if ($category === 'all') {
            $sql = "SELECT * FROM documents ORDER BY created_at DESC";
            return $this->fetchAll($sql);
        } else {
            $sql = "SELECT * FROM documents WHERE category = ? ORDER BY created_at DESC";
            return $this->fetchAll($sql, [$category]);
        }
    }
    
    public function getAllDocuments() {
        $sql = "SELECT * FROM documents ORDER BY created_at DESC";
        return $this->fetchAll($sql);
    }
    
    public function getDocumentById($id) {
        $sql = "SELECT * FROM documents WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM documents WHERE category IS NOT NULL AND category != '' ORDER BY category";
        return $this->fetchAll($sql);
    }
    
    public function getTotalDocuments() {
        $sql = "SELECT COUNT(*) as total FROM documents";
        $result = $this->fetch($sql);
        return $result['total'] ?? 0;
    }
    
    public function createDocument($data) {
        $sql = "INSERT INTO documents (title, file_path, file_type, category) VALUES (?, ?, ?, ?)";
        return $this->execute($sql, [
            $data['title'],
            $data['file_path'],
            $data['file_type'],
            $data['category']
        ]);
    }
    
    public function deleteDocument($id) {
        $sql = "DELETE FROM documents WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    public function incrementDownload($id) {
        $sql = "UPDATE documents SET download_count = download_count + 1 WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    public function getDownloadStats() {
        $sql = "SELECT title, download_count, created_at 
                FROM documents 
                ORDER BY download_count DESC 
                LIMIT 10";
        return $this->fetchAll($sql);
    }
}
