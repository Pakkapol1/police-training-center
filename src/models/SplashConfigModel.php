<?php
require_once __DIR__ . '/BaseModel.php';

class SplashConfigModel extends BaseModel {
    public function getConfig() {
        $sql = "SELECT * FROM splash_config ORDER BY id DESC LIMIT 1";
        return $this->fetch($sql);
    }

    public function updateConfig($enabled, $image_path = null, $royal_duties_url = null, $royal_duties_enabled = null) {
        $config = $this->getConfig();
        if (!$config) {
            // ถ้าไม่มี row ให้ insert
            $sql = "INSERT INTO splash_config (enabled, image_path, royal_duties_url, royal_duties_enabled) VALUES (:enabled, :image_path, :royal_duties_url, :royal_duties_enabled)";
            return $this->execute($sql, [
                ':enabled' => $enabled, 
                ':image_path' => $image_path ?: '',
                ':royal_duties_url' => $royal_duties_url ?: '',
                ':royal_duties_enabled' => $royal_duties_enabled ?: 1
            ]);
        }
        $sql = "UPDATE splash_config SET enabled = :enabled";
        $params = [':enabled' => $enabled];
        if ($image_path !== null) {
            $sql .= ", image_path = :image_path";
            $params[':image_path'] = $image_path;
        }
        if ($royal_duties_url !== null) {
            $sql .= ", royal_duties_url = :royal_duties_url";
            $params[':royal_duties_url'] = $royal_duties_url;
        }
        if ($royal_duties_enabled !== null) {
            $sql .= ", royal_duties_enabled = :royal_duties_enabled";
            $params[':royal_duties_enabled'] = $royal_duties_enabled;
        }
        $sql .= " WHERE id = :id";
        $params[':id'] = $config['id'];
        return $this->execute($sql, $params);
    }
} 