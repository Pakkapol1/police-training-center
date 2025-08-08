<?php
require_once 'BaseModel.php';

class CommanderModel extends BaseModel {
    
    public function createCommander($data) {
        try {
            // ตรวจสอบว่าตารางมีคอลัมน์ rank_id หรือไม่
            $checkColumn = $this->db->query("SHOW COLUMNS FROM commanders LIKE 'rank_id'");
            $hasRankId = $checkColumn->rowCount() > 0;
            
            if ($hasRankId) {
                // ใช้ rank_id
                $sql = "INSERT INTO commanders (position_name, rank_id, full_name, qualifications, 
                        previous_positions, work_phone, email, photo, status, `group`, sort_order, parent_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?, ?)";
                
                return $this->execute($sql, [
                    $data['position_name'],
                    $data['rank_id'] ?? null,
                    $data['full_name'],
                    $data['qualifications'] ?? '',
                    $data['previous_positions'] ?? '',
                    $data['work_phone'] ?? '',
                    $data['email'] ?? '',
                    $data['photo'] ?? null,
                    $data['group'] ?? null,
                    $data['sort_order'] ?? null,
                    $data['parent_id'] ?? null
                ]);
            } else {
                // ใช้ rank_name แทน
                $sql = "INSERT INTO commanders (position_name, rank_name, full_name, qualifications, 
                        previous_positions, work_phone, email, photo, status, `group`, sort_order, parent_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?, ?)";
                
                // ดึงชื่อยศจาก rank_id
                $rankName = '';
                if (!empty($data['rank_id'])) {
                    $rankSql = "SELECT rank_name_short FROM police_ranks WHERE id = ?";
                    $rankResult = $this->fetch($rankSql, [$data['rank_id']]);
                    $rankName = $rankResult['rank_name_short'] ?? '';
                }
                
                return $this->execute($sql, [
                    $data['position_name'],
                    $rankName,
                    $data['full_name'],
                    $data['qualifications'] ?? '',
                    $data['previous_positions'] ?? '',
                    $data['work_phone'] ?? '',
                    $data['email'] ?? '',
                    $data['photo'] ?? null,
                    $data['group'] ?? null,
                    $data['sort_order'] ?? null,
                    $data['parent_id'] ?? null
                ]);
            }
        } catch (Exception $e) {
            error_log("CommanderModel createCommander error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllCommanders($allStatus = true) {
        try {
            // ตรวจสอบว่าตารางมีคอลัมน์ rank_id หรือไม่
            $checkColumn = $this->db->query("SHOW COLUMNS FROM commanders LIKE 'rank_id'");
            $hasRankId = $checkColumn->rowCount() > 0;
            
            if ($hasRankId) {
                // ใช้ rank_id - JOIN กับตาราง police_ranks
                $statusCondition = $allStatus ? "" : "WHERE c.status = 'active'";
                $sql = "SELECT c.*, r.rank_name_short as rank_name, r.rank_name_full 
                        FROM commanders c 
                        LEFT JOIN police_ranks r ON c.rank_id = r.id 
                        $statusCondition 
                        ORDER BY c.sort_order ASC, c.id ASC";
            } else {
                // ใช้ rank_name โดยตรง
                $sql = $allStatus
                    ? "SELECT * FROM commanders ORDER BY sort_order ASC, id ASC"
                    : "SELECT * FROM commanders WHERE status = 'active' ORDER BY sort_order ASC, id ASC";
            }
            
            return $this->fetchAll($sql);
            
        } catch (Exception $e) {
            error_log("CommanderModel getAllCommanders error: " . $e->getMessage());
            // Fallback to basic query
        $sql = $allStatus
            ? "SELECT * FROM commanders ORDER BY sort_order ASC, id ASC"
            : "SELECT * FROM commanders WHERE status = 'active' ORDER BY sort_order ASC, id ASC";
        return $this->fetchAll($sql);
        }
    }
    
    public function getCommandersByLevel($level) {
        $sql = "SELECT * FROM commanders WHERE status = 'active' ORDER BY sort_order ASC, id ASC";
        $allCommanders = $this->fetchAll($sql);
        
        $result = [];
        foreach ($allCommanders as $commander) {
            $order = intval($commander['display_order']);
            
            switch($level) {
                case 1: // ผู้บังคับบัญชา ศฝร.ภ.8
                    if ($order >= 1 && $order <= 1) $result[] = $commander;
                    break;
                case 2: // ผู้บังคับบัญชาฝ่ายต่างๆ
                    if ($order >= 2 && $order <= 5) $result[] = $commander;
                    break;
                case 3: // หัวหน้าฝ่าย/กลุ่มงาน
                    if ($order >= 6 && $order <= 15) $result[] = $commander;
                    break;
                case 4: // ผู้บังคับหมวด
                    if ($order >= 16 && $order <= 25) $result[] = $commander;
                    break;
                case 5: // เจ้าหน้าที่/ครูฝึก
                    if ($order >= 26) $result[] = $commander;
                    break;
            }
        }
        return $result;
    }
    
    
    
    public function getCommanderById($id) {
        try {
            // ตรวจสอบว่าตารางมีคอลัมน์ rank_id หรือไม่
            $checkColumn = $this->db->query("SHOW COLUMNS FROM commanders LIKE 'rank_id'");
            $hasRankId = $checkColumn->rowCount() > 0;
            
            if ($hasRankId) {
                // ใช้ rank_id - JOIN กับตาราง police_ranks
                $sql = "SELECT c.*, r.rank_name_short as rank_name, r.rank_name_full 
                        FROM commanders c 
                        LEFT JOIN police_ranks r ON c.rank_id = r.id 
                        WHERE c.id = ?";
            } else {
                // ใช้ rank_name โดยตรง
                $sql = "SELECT * FROM commanders WHERE id = ?";
            }
            
            return $this->fetch($sql, [$id]);
            
        } catch (Exception $e) {
            error_log("CommanderModel getCommanderById error: " . $e->getMessage());
            // Fallback to basic query
        $sql = "SELECT * FROM commanders WHERE id = ?";
        return $this->fetch($sql, [$id]);
        }
    }
    
    public function getTopCommander() {
        try {
            // ตรวจสอบว่าตารางมีคอลัมน์ rank_id หรือไม่
            $checkColumn = $this->db->query("SHOW COLUMNS FROM commanders LIKE 'rank_id'");
            $hasRankId = $checkColumn->rowCount() > 0;
            
            if ($hasRankId) {
                // ใช้ rank_id - JOIN กับตาราง police_ranks
                $sql = "SELECT c.*, r.rank_name_short as rank_name, r.rank_name_full 
                        FROM commanders c 
                        LEFT JOIN police_ranks r ON c.rank_id = r.id 
                        WHERE c.parent_id IS NULL AND c.status = 'active' 
                        ORDER BY c.display_order ASC, c.id ASC LIMIT 1";
            } else {
                // ใช้ rank_name โดยตรง
                $sql = "SELECT * FROM commanders 
                        WHERE parent_id IS NULL AND status = 'active' 
                        ORDER BY display_order ASC, id ASC LIMIT 1";
            }
            
            return $this->fetch($sql);
            
        } catch (Exception $e) {
            error_log("CommanderModel getTopCommander error: " . $e->getMessage());
            // Fallback to basic query
            $sql = "SELECT * FROM commanders 
                    WHERE parent_id IS NULL AND status = 'active' 
                    ORDER BY display_order ASC, id ASC LIMIT 1";
            return $this->fetch($sql);
        }
    }
    
    
    
    public function updateCommander($id, $data) {
        try {
            // ตรวจสอบว่าตารางมีคอลัมน์ rank_id หรือไม่
            $checkColumn = $this->db->query("SHOW COLUMNS FROM commanders LIKE 'rank_id'");
            $hasRankId = $checkColumn->rowCount() > 0;
            
        $fields = [];
        $params = [];
        
            // กำหนดรายชื่อ fields ตามโครงสร้างตาราง
            $fieldList = $hasRankId 
                ? ['position_name', 'rank_id', 'full_name', 'qualifications', 'previous_positions', 'work_phone', 'email', 'photo', 'group']
                : ['position_name', 'rank_name', 'full_name', 'qualifications', 'previous_positions', 'work_phone', 'email', 'photo', 'group'];
            
            foreach ($fieldList as $field) {
            if (isset($data[$field])) {
                    // ใส่ backticks สำหรับ group เพราะเป็น reserved keyword
                    $fieldName = ($field === 'group') ? "`$field`" : $field;
                    $fields[] = "$fieldName = ?";
                $params[] = $data[$field];
            }
        }
            
            // จัดการกรณี rank_id vs rank_name
            if (!$hasRankId && isset($data['rank_id'])) {
                // ตารางใช้ rank_name แต่ส่ง rank_id มา -> แปลงเป็น rank_name
                $rankName = '';
                if (!empty($data['rank_id'])) {
                    $rankSql = "SELECT rank_name_short FROM police_ranks WHERE id = ?";
                    $rankResult = $this->fetch($rankSql, [$data['rank_id']]);
                    $rankName = $rankResult['rank_name_short'] ?? '';
                }
                $fields[] = "rank_name = ?";
                $params[] = $rankName;
            }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE commanders SET " . implode(', ', $fields) . " WHERE id = ?";
        return $this->execute($sql, $params);
            
        } catch (Exception $e) {
            error_log("CommanderModel updateCommander error: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteCommander($id) {
        $sql = "DELETE FROM commanders WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    public function updateSortOrder($id, $sortOrder) {
        $sql = "UPDATE commanders SET sort_order = ? WHERE id = ?";
        return $this->execute($sql, [$sortOrder, $id]);
    }
    
    public function updateParentId($id, $parentId) {
        $sql = "UPDATE commanders SET parent_id = ? WHERE id = ?";
        return $this->execute($sql, [$parentId, $id]);
    }
    
    public function updateGroup($id, $group) {
        $sql = "UPDATE commanders SET `group` = ? WHERE id = ?";
        return $this->execute($sql, [$group, $id]);
    }
    
    /**
     * นับจำนวนผู้บังคับบัญชาทั้งหมด
     */
    public function getTotalCommanders() {
        $sql = "SELECT COUNT(*) as total FROM commanders WHERE status = 'active'";
        $result = $this->fetch($sql);
        return $result['total'] ?? 0;
    }
}
