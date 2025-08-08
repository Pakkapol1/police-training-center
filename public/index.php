<?php
// Start session for splash page tracking
session_start();

// Splash page logic: check if splash is enabled, show only once per session on main page
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/models/SplashConfigModel.php';

try {
    $model = new SplashConfigModel();
    $config = $model->getConfig();
    
    // Only redirect to splash if it's enabled, user didn't come from splash page, accessing main page, and hasn't seen splash in this session
    $page = $_GET['page'] ?? 'home';
    if ($config && $config['enabled'] && !isset($_GET['from_splash']) && $page === 'home' && !isset($_SESSION['splash_shown'])) {
        header('Location: /index-1.php');
        exit;
    }
} catch (Exception $e) {
    // If there's an error, continue to main site
    error_log("Splash config error: " . $e->getMessage());
}

// เพิ่มการแสดง error เพื่อ debug
date_default_timezone_set('Asia/Bangkok');
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('SRC_PATH', __DIR__ . '/../src');

require_once SRC_PATH . '/controllers/HomeController.php';

try {
    $controller = new HomeController();
    $page = $_GET['page'] ?? 'home';
    $action = $_GET['action'] ?? '';

    switch($page) {
        case 'home':
            $controller->index();
            break;
        // เมนูย่อยของเมนูหลัก
        case 'commanders':
            $controller->commanders();
            break;
        case 'com':
            $controller->com();
            break;
        case 'directory-officers':
            $controller->directoryOfficers();
            break;
        case 'directory-enlisted':
            $controller->directoryEnlisted();
            break;
        case 'directory-commanders':
            $controller->directoryCommanders();
            break;
        case 'vision-philosophy':
            $controller->visionPhilosophy();
            break;
        case 'location-map':
            $controller->locationMap();
            break;
        
        // เมนูอื่นๆ
        case 'news':
            if ($action === 'detail') {
                $controller->newsDetail();
            } else {
                $controller->news();
            }
            break;
        case 'announcements':
            if ($action === 'detail') {
                $controller->announcementDetail();
            } else {
                $controller->announcements();
            }
            break;



        case 'documents':
            $controller->documents();
            break;
        case 'about':
            $controller->about();
            break;
        case 'contact':
            $controller->contact();
            break;
        default:
            $controller->index();
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "<br>File: " . $e->getFile();
    echo "<br>Line: " . $e->getLine();
}
?>
