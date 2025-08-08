<?php
require_once 'BaseModel.php';

class DirectoryModel extends BaseModel {
    
    // ===== ฟังก์ชันสำหรับผู้กำกับการ =====
    
    public function getAllSupervisors() {
        $sql = "SELECT * FROM directory_supervisors WHERE status = 'active' ORDER BY order_number ASC";
        return $this->fetchAll($sql);
    }
    
    public function getSupervisorById($id) {
        $sql = "SELECT * FROM directory_supervisors WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function createSupervisor($data) {
        // สร้าง service_period จาก start_date และ end_date
        $service_period = $this->formatServicePeriod($data['start_date'], $data['end_date'], $data['is_current'] ?? false);
        
        $sql = "INSERT INTO directory_supervisors (order_number, `rank`, first_name, last_name, service_period, start_date, end_date, is_current) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [
            $data['order_number'],
            $data['rank'],
            $data['first_name'],
            $data['last_name'],
            $service_period,
            $data['start_date'] ?: null,
            $data['end_date'] ?: null,
            $data['is_current'] ?? 0
        ]);
    }
    
    public function updateSupervisor($id, $data) {
        // สร้าง service_period จาก start_date และ end_date
        $service_period = $this->formatServicePeriod($data['start_date'], $data['end_date'], $data['is_current'] ?? false);
        
        $sql = "UPDATE directory_supervisors SET order_number = ?, `rank` = ?, first_name = ?, last_name = ?, service_period = ?, start_date = ?, end_date = ?, is_current = ? WHERE id = ?";
        return $this->execute($sql, [
            $data['order_number'],
            $data['rank'],
            $data['first_name'],
            $data['last_name'],
            $service_period,
            $data['start_date'] ?: null,
            $data['end_date'] ?: null,
            $data['is_current'] ?? 0,
            $id
        ]);
    }
    
    public function deleteSupervisor($id) {
        $sql = "DELETE FROM directory_supervisors WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    // ===== ฟังก์ชันสำหรับผู้บังคับการ =====
    
    public function getAllCommanders() {
        $sql = "SELECT * FROM directory_commanders WHERE status = 'active' ORDER BY order_number ASC";
        return $this->fetchAll($sql);
    }
    
    public function getCommanderById($id) {
        $sql = "SELECT * FROM directory_commanders WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function createCommander($data) {
        // สร้าง service_period จาก start_date และ end_date
        $service_period = $this->formatServicePeriod($data['start_date'], $data['end_date'], $data['is_current'] ?? false);
        
        $sql = "INSERT INTO directory_commanders (order_number, `rank`, first_name, last_name, service_period, start_date, end_date, is_current) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [
            $data['order_number'],
            $data['rank'],
            $data['first_name'],
            $data['last_name'],
            $service_period,
            $data['start_date'] ?: null,
            $data['end_date'] ?: null,
            $data['is_current'] ?? 0
        ]);
    }
    
    public function updateCommander($id, $data) {
        // สร้าง service_period จาก start_date และ end_date
        $service_period = $this->formatServicePeriod($data['start_date'], $data['end_date'], $data['is_current'] ?? false);
        
        $sql = "UPDATE directory_commanders SET order_number = ?, `rank` = ?, first_name = ?, last_name = ?, service_period = ?, start_date = ?, end_date = ?, is_current = ? WHERE id = ?";
        return $this->execute($sql, [
            $data['order_number'],
            $data['rank'],
            $data['first_name'],
            $data['last_name'],
            $service_period,
            $data['start_date'] ?: null,
            $data['end_date'] ?: null,
            $data['is_current'] ?? 0,
            $id
        ]);
    }
    
    public function deleteCommander($id) {
        $sql = "DELETE FROM directory_commanders WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    // ===== ฟังก์ชันทั่วไป =====
    
    public function getNextSupervisorOrderNumber() {
        $sql = "SELECT MAX(order_number) as max_order FROM directory_supervisors";
        $result = $this->fetch($sql);
        return ($result['max_order'] ?? 0) + 1;
    }
    
    public function getNextCommanderOrderNumber() {
        $sql = "SELECT MAX(order_number) as max_order FROM directory_commanders";
        $result = $this->fetch($sql);
        return ($result['max_order'] ?? 0) + 1;
    }
    
    public function updateSupervisorStatus($id, $status) {
        $sql = "UPDATE directory_supervisors SET status = ? WHERE id = ?";
        return $this->execute($sql, [$status, $id]);
    }
    
    public function updateCommanderStatus($id, $status) {
        $sql = "UPDATE directory_commanders SET status = ? WHERE id = ?";
        return $this->execute($sql, [$status, $id]);
    }
    
    // ===== ฟังก์ชันช่วยเหลือ =====
    
    /**
     * แปลงวันที่เป็นรูปแบบข้อความสำหรับแสดงผล
     */
    public function formatServicePeriod($start_date, $end_date, $is_current = false) {
        if (empty($start_date) && empty($end_date)) {
            return '';
        }
        
        $start_thai = $this->formatThaiDate($start_date);
        $end_thai = $this->formatThaiDate($end_date);
        
        if (!empty($start_thai) && !empty($end_thai)) {
            return "$start_thai - $end_thai";
        } elseif (!empty($start_thai)) {
            if ($is_current) {
                return "$start_thai - ปัจจุบัน";
            }
            return "$start_thai";
        } elseif (!empty($end_thai)) {
            return "ถึง $end_thai";
        }
        
        return '';
    }
    
    /**
     * แปลงวันที่เป็นรูปแบบไทย
     */
    private function formatThaiDate($date) {
        if (empty($date) || $date === '0000-00-00') {
            return '';
        }
        
        $thai_months = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.',
            4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
            7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.',
            10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
        ];
        
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return '';
        }
        
        $day = (int)date('j', $timestamp);
        $month = $thai_months[(int)date('n', $timestamp)];
        $year = (int)date('Y', $timestamp) + 543; // แปลงเป็นปี พ.ศ.
        
        return "$day $month$year";
    }
} 