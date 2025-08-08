<?php
require_once __DIR__ . '/BaseModel.php';

class PopupModel extends BaseModel {
    private $table = 'popups';

    public function getActivePopup() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY updated_at DESC LIMIT 1";
        return $this->fetch($sql);
    }

    public function getPopupById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }

    public function getAllPopups() {
        $sql = "SELECT * FROM {$this->table} ORDER BY updated_at DESC";
        return $this->fetchAll($sql);
    }

    public function createPopup($message, $image, $status = 'inactive') {
        $sql = "INSERT INTO {$this->table} (message, image, status) VALUES (?, ?, ?)";
        return $this->execute($sql, [$message, $image, $status]);
    }

    public function updatePopup($id, $message, $image, $status) {
        $sql = "UPDATE {$this->table} SET message = ?, image = ?, status = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$message, $image, $status, $id]);
    }

    public function deactivateAll() {
        $sql = "UPDATE {$this->table} SET status = 'inactive'";
        return $this->execute($sql);
    }
} 