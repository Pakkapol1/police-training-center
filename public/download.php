<?php
// ตัวอย่าง: /download.php?file=uploads/officer_directory/xxxx.pdf&name=ชื่อไฟล์.pdf
$file = isset($_GET['file']) ? $_GET['file'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : basename($file);
$fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $file;
error_log('DOWNLOAD DEBUG: ' . $fullPath);
if (file_exists($fullPath)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($name) . '"');
    header('Content-Length: ' . filesize($fullPath));
    readfile($fullPath);
    exit;
} else {
    error_log('DOWNLOAD ERROR: File not found: ' . $fullPath);
    http_response_code(404);
    echo 'File not found: ' . htmlspecialchars($fullPath);
} 