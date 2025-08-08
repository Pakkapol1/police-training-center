<?php
// สร้างตารางและข้อมูลตัวอย่างสำหรับระบบทำเนียบ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__DIR__));
define('SRC_PATH', ROOT_PATH . '/src');

echo "<h1>Setup ระบบทำเนียบผู้บังคับบัญชา</h1>";

// เชื่อมต่อฐานข้อมูล
try {
    require_once SRC_PATH . '/config/database.php';
    echo "<p style='color: green;'>✓ เชื่อมต่อฐานข้อมูลสำเร็จ</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ เชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage() . "</p>";
    exit;
}

// สร้างตาราง directory_supervisors
echo "<h2>1. สร้างตาราง directory_supervisors</h2>";
try {
    $sql = "CREATE TABLE IF NOT EXISTS directory_supervisors (
        id INT PRIMARY KEY AUTO_INCREMENT,
        order_number INT NOT NULL,
        `rank` VARCHAR(50) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        service_period VARCHAR(200) NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_order_number (order_number),
        INDEX idx_status (status)
    )";
    
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ สร้างตาราง directory_supervisors สำเร็จ</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ สร้างตาราง directory_supervisors ล้มเหลว: " . $e->getMessage() . "</p>";
}

// สร้างตาราง directory_commanders
echo "<h2>2. สร้างตาราง directory_commanders</h2>";
try {
    $sql = "CREATE TABLE IF NOT EXISTS directory_commanders (
        id INT PRIMARY KEY AUTO_INCREMENT,
        order_number INT NOT NULL,
        `rank` VARCHAR(50) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        service_period VARCHAR(200) NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_order_number (order_number),
        INDEX idx_status (status)
    )";
    
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ สร้างตาราง directory_commanders สำเร็จ</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ สร้างตาราง directory_commanders ล้มเหลว: " . $e->getMessage() . "</p>";
}

// เพิ่มข้อมูลตัวอย่างผู้กำกับการ
echo "<h2>3. เพิ่มข้อมูลผู้กำกับการ</h2>";
try {
    // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM directory_supervisors");
    $count = $stmt->fetch()['count'];
    
    if ($count == 0) {
        $supervisors = [
            [1, 'พ.อ.อ.', 'วุฒิกร', 'เทพคูหร', '1 ต.ค.2527 - 30 ก.ย.2529'],
            [2, 'พ.อ.อ.', 'พิธาน', 'มูลดิส', '1 ต.ค.2529 - 31 ก.ย.2534'],
            [3, 'พ.อ.อ.', 'อมรชัย', 'ภีรังษี', '1 ต.ค.2534 - 15 ก.ย.2537'],
            [4, 'พ.อ.อ.', 'ทวีเกียรติ', 'สุขสมบูรณ์', '16 ก.ย.2537 - 15 ต.ค.2538'],
            [5, 'พ.อ.อ.', 'ชาญชัย', 'ใจพิสุทธิ์', '16 ต.ค.2538 - 1 พ.ย.2542'],
            [6, 'พ.อ.อ.', 'วิรัช', 'ปฏิกานต์', '1 พ.ย.2542 - 14 ธ.ค.2543'],
            [7, 'พ.อ.อ.', 'พิธาน', 'ขันสร้างคำ', '15 ธ.ค.2543 - 15 พ.ย.2544'],
            [8, 'พ.อ.อ.', 'เพียรชน', 'คล่องอาคาร', '16 พ.ย.2544 - 18 ธ.ค.2546'],
            [9, 'พ.อ.อ.', 'อารมณ์', 'แมจจกกุล', '19 ธ.ค.2546 - 31 ต.ค.2548'],
            [10, 'พ.อ.อ.', 'อนุชาติ', 'รัตนอภา', '1 พ.ย.2548 - 31 ต.ค.2549'],
            [11, 'พ.อ.อ.', 'เสน่ห์', 'นาโสต', '1 พ.ย.2549- 31 ต.ค.2551'],
            [12, 'พ.อ.อ.', 'อนุชาติ', 'รัตนอภา', '1 ต.ค.2551 -15 ก.ย.2553']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO directory_supervisors (order_number, `rank`, first_name, last_name, service_period) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($supervisors as $supervisor) {
            $stmt->execute($supervisor);
        }
        
        echo "<p style='color: green;'>✓ เพิ่มข้อมูลผู้กำกับการ " . count($supervisors) . " รายการ</p>";
    } else {
        echo "<p style='color: orange;'>! มีข้อมูลผู้กำกับการอยู่แล้ว {$count} รายการ</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ เพิ่มข้อมูลผู้กำกับการล้มเหลว: " . $e->getMessage() . "</p>";
}

// เพิ่มข้อมูลตัวอย่างผู้บังคับการ
echo "<h2>4. เพิ่มข้อมูลผู้บังคับการ</h2>";
try {
    // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM directory_commanders");
    $count = $stmt->fetch()['count'];
    
    if ($count == 0) {
        $commanders = [
            [1, 'พล.ต.ต.', 'เกรียงไกร', 'แก้วสร้าง', '7 ก.ย.2552 - 30 ต.ค.2552'],
            [2, 'พล.ต.ต.', 'วิชิต', 'ธรรมรักษา', '25 พ.ย.2552 - 30 พ.ย.2553'],
            [3, 'พล.ต.ต.', 'สุวรรณ', 'ลิลารอด', '1 พ.ย.2553 - 30 ก.ย.2555'],
            [4, 'พล.ต.ต.', 'อิทธิเดช', 'บริสุทธิ์จากบาป', '1 ต.ค.2555 - 30 ก.ย.2556'],
            [5, 'พล.ต.ต.', 'สุพิชญ์', 'ขาบุลรอด', '1 ต.ค.2556 - 30 ก.ย.2559'],
            [6, 'พล.ต.ต.', 'ภูรินทร์', 'แรงปาง', '3 ต.ค.2559 - 30 ก.ย.2561'],
            [7, 'พล.ต.ต.', 'สนธิวินิจ', 'ปวนประลองเพท', '1 ต.ค.2561 - 30 ก.ย.2562'],
            [8, 'พล.ต.ต.', 'มิตรชาติ', 'บุญญานุสนธิ์', '1 ต.ค.2562 - 30 ก.ย. 2563'],
            [9, 'พล.ต.ต.', 'ภิรมย์', 'ภาคยะรถยี', '1 ต.ค.2563 - 30 ก.ย.2565'],
            [10, 'พล.ต.ต.', 'ศักดิ์ชัย', 'อินทร์ฉิม', '1 ต.ค.2565 - 10 ม.ค.2566'],
            [11, 'พล.ต.ต.', 'นิพนธ์', 'รัตนวิจิตสงไซ', '11 ม.ค.2566 - 3 พ.ย.2566'],
            [12, 'พล.ต.ต.', 'สุรภาพ', 'ศิขรี', '4 พ.ย.2566 - ปัจจุบัน']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO directory_commanders (order_number, `rank`, first_name, last_name, service_period) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($commanders as $commander) {
            $stmt->execute($commander);
        }
        
        echo "<p style='color: green;'>✓ เพิ่มข้อมูลผู้บังคับการ " . count($commanders) . " รายการ</p>";
    } else {
        echo "<p style='color: orange;'>! มีข้อมูลผู้บังคับการอยู่แล้ว {$count} รายการ</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ เพิ่มข้อมูลผู้บังคับการล้มเหลว: " . $e->getMessage() . "</p>";
}

echo "<h2>5. สรุปผลการติดตั้ง</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM directory_supervisors");
    $supervisorCount = $stmt->fetch()['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM directory_commanders");
    $commanderCount = $stmt->fetch()['count'];
    
    echo "<p style='color: green;'>✓ ระบบทำเนียบพร้อมใช้งาน</p>";
    echo "<p>- ผู้กำกับการ: {$supervisorCount} รายการ</p>";
    echo "<p>- ผู้บังคับการ: {$commanderCount} รายการ</p>";
    
    echo "<h3>ลิงก์ทดสอบ:</h3>";
    echo "<p><a href='/admin/test-directory.php'>ทดสอบระบบ</a></p>";
    echo "<p><a href='/admin?action=directory&type=supervisors'>จัดการผู้กำกับการ</a></p>";
    echo "<p><a href='/admin?action=directory&type=commanders'>จัดการผู้บังคับการ</a></p>";
    echo "<p><a href='/?page=directory-commanders'>ดูทำเนียบผู้บังคับบัญชา</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ เกิดข้อผิดพลาดในการสรุปผล: " . $e->getMessage() . "</p>";
}

?>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #333; }
h2 { color: #666; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
h3 { color: #888; }
a { color: #0066cc; text-decoration: none; }
a:hover { text-decoration: underline; }
</style> 