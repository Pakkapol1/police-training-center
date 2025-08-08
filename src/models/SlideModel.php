<?php
require_once SRC_PATH . '/models/BaseModel.php';

class SlideModel extends BaseModel {
    private $table = 'slides';
    
    /**
     * ดึงสไลด์ทั้งหมดพร้อม pagination
     */
    public function getSlidesWithPagination($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} ORDER BY sort_order ASC, created_at DESC LIMIT ? OFFSET ?";
        return $this->fetchAll($sql, [$perPage, $offset]);
    }

    /**
     * นับจำนวนสไลด์ทั้งหมด
     */
    public function getTotalSlides() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * ดึงสไลด์ตาม ID
     */
    public function getSlideById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }

    /**
     * ดึงสไลด์ที่เปิดใช้งาน
     */
    public function getActiveSlides() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC";
        return $this->fetchAll($sql);
    }

    /**
     * เพิ่มสไลด์ใหม่
     */
    public function addSlide($data) {
        $sql = "INSERT INTO {$this->table} (title, subtitle, description, image_path, link_url, qr_code_url, sort_order, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['title'],
            $data['subtitle'],
            $data['description'],
            $data['image_path'],
            $data['link_url'],
            $data['qr_code_url'],
            $data['sort_order'],
            $data['status']
        ];

        return $this->execute($sql, $params);
    }

    /**
     * อัปเดตสไลด์
     */
    public function updateSlide($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                title = ?, subtitle = ?, description = ?, image_path = ?, 
                link_url = ?, qr_code_url = ?, sort_order = ?, status = ?, 
                updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        
        $params = [
            $data['title'],
            $data['subtitle'],
            $data['description'],
            $data['image_path'],
            $data['link_url'],
            $data['qr_code_url'],
            $data['sort_order'],
            $data['status'],
            $id
        ];

        return $this->execute($sql, $params);
    }

    /**
     * ลบสไลด์
     */
    public function deleteSlide($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * อัปเดตสถานะสไลด์
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        return $this->execute($sql, [$status, $id]);
    }

    /**
     * อัปเดตลำดับสไลด์
     */
    public function updateSortOrder($id, $sortOrder) {
        $sql = "UPDATE {$this->table} SET sort_order = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        return $this->execute($sql, [$sortOrder, $id]);
    }

    /**
     * ดึงลำดับสูงสุด
     */
    public function getMaxSortOrder() {
        $sql = "SELECT MAX(sort_order) as max_order FROM {$this->table}";
        $result = $this->fetch($sql);
        return $result['max_order'] ?? 0;
    }
}
?>