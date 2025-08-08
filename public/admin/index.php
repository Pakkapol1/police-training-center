<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

// เปิด error reporting เพื่อ debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', dirname(dirname(__DIR__)));
define('SRC_PATH', ROOT_PATH . '/src');

if (file_exists(SRC_PATH . '/config/database.php')) {
    require_once SRC_PATH . '/config/database.php';
}

if (file_exists(SRC_PATH . '/controllers/AdminController.php')) {
    require_once SRC_PATH . '/controllers/AdminController.php';
} else {
    die('ไม่พบไฟล์ AdminController');
}

$action = $_GET['action'] ?? 'login';
if (!isset($_SESSION['admin_id']) && $action !== 'login') {
    header('Location: /admin?action=login');
    exit;
}

try {
    $controller = new AdminController();
    
    switch($action) {
        case 'login':
            $controller->login();
            break;
        case 'logout':
            $controller->logout();
            break;
        case 'dashboard':
            $controller->dashboard();
            break;
        case 'news':
            $controller->manageNews();
            break;
        case 'announcements':
            $controller->manageAnnouncements();
            break;

        case 'documents':
            $controller->manageDocuments();
            break;
        case 'users':
            $controller->manageUsers();
            break;
        case 'commanders':
            $controller->manageCommanders();
            break;
    
        case 'ranks':
            $controller->manageRanks();
            break;
        case 'popup':
            $controller->managePopup();
            break;
        case 'officer-directory':
            $controller->manageOfficerDirectory();
            break;
        case 'enlisted-directory':
            $controller->manageEnlistedDirectory();
            break;
        case 'directory':
            $controller->manageDirectory();
            break;

        case 'manageSplash':
            $controller->manageSplash();
            break;
        case 'admin_splash_save':
            $controller->saveSplash();
            break;
        case 'activity-log':
            $controller->activityLog();
            break;
        case 'slides':
            $controller->manageSlides();
            break;


            
        default:
            $controller->dashboard();

    }
} catch (Exception $e) {
    echo "<h1>เกิดข้อผิดพลาด</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><a href='/admin?action=dashboard'>กลับสู่แดชบอร์ด</a></p>";
}
?>
