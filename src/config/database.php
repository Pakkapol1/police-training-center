<?php
$host = getenv('DB_HOST') ?: 'mysql';
$dbname = getenv('DB_NAME') ?: 'police_training';
$username = getenv('DB_USER') ?: 'police_user';
$password = getenv('DB_PASS') ?: 'police_pass';
$port = getenv('DB_PORT') ?: '3306';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", 
        $username, 
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4, time_zone = '+07:00'",
            PDO::ATTR_TIMEOUT => 5,
        ]
    );
    
    // ตั้งค่า timezone สำหรับ PHP
    date_default_timezone_set('Asia/Bangkok');
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้ กรุณาลองใหม่อีกครั้ง");
}
?>
