<?php
require_once 'BaseModel.php';

class AdminModel extends BaseModel {
    
    public function authenticate($username, $password) {
        try {
            $sql = "SELECT * FROM admins WHERE username = ?";
            $admin = $this->fetch($sql, [$username]);
            
            if ($admin && password_verify($password, $admin['password'])) {
                return $admin;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Authentication error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllAdmins() {
        $sql = "SELECT id, username, full_name, email, role, created_at FROM admins ORDER BY created_at DESC";
        return $this->fetchAll($sql);
    }
    
    public function getAdminById($id) {
        $sql = "SELECT id, username, full_name, email, role, created_at FROM admins WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function createAdmin($data) {
        $sql = "INSERT INTO admins (username, password, full_name, email, role) VALUES (?, ?, ?, ?, ?)";
        return $this->execute($sql, [
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['full_name'],
            $data['email'],
            $data['role'] ?? 'editor'
        ]);
    }
    
    public function updateAdmin($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $sql = "UPDATE admins SET username = ?, password = ?, full_name = ?, email = ?, role = ? WHERE id = ?";
            return $this->execute($sql, [
                $data['username'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['full_name'],
                $data['email'],
                $data['role'],
                $id
            ]);
        } else {
            $sql = "UPDATE admins SET username = ?, full_name = ?, email = ?, role = ? WHERE id = ?";
            return $this->execute($sql, [
                $data['username'],
                $data['full_name'],
                $data['email'],
                $data['role'],
                $id
            ]);
        }
    }
    
    public function deleteAdmin($id) {
        $sql = "DELETE FROM admins WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM admins";
        $result = $this->fetch($sql);
        return $result['total'] ?? 0;
    }
}
