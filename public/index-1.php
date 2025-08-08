<?php
// Start session for splash page tracking
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../src/config/database.php';
try {
    require_once __DIR__ . '/../src/models/SplashConfigModel.php';
    $model = new SplashConfigModel();
    $config = $model->getConfig();
    
    // If splash is disabled, redirect to main site
    if (!$config || !$config['enabled']) {
        header('Location: /index.php');
        exit;
    }
    
    $image_path = $config['image_path'] ?: '/assets/img/113.jpg';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Set session to remember user has seen splash page
        $_SESSION['splash_shown'] = true;
        // Go directly to main site with from_splash parameter (no page parameter)
        header('Location: /index.php?from_splash=1');
        exit;
    }
} catch (Throwable $e) {
    // If there's an error, redirect to main site
    header('Location: /index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ทรงพระเจริญ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'TH SarabunPSK', 'Sarabun', Tahoma, sans-serif;
            margin: 0;
            background: #f7e3b0;
            min-height: 100vh;
        }
        .splash-content {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .splash-img {
            width: 100vw;
            height: auto;
            max-width: 100vw;
            max-height: 95vh;
            object-fit: contain;
            border-radius: 0;
            margin-bottom: 40px;
            margin-top: 40px;
            margin-left: 0;
            margin-right: 0;
        }
        .splash-btn-group {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-top: 0;
            align-items: center;
        }
        .splash-btn {
            font-size: 1.35rem;
            font-weight: bold;
            padding: 18px 48px;
            border: 3px solid #FFD700;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(.4,0,.2,1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 200px;
            min-height: 60px;
            text-align: center;
            font-family: inherit;
            box-shadow: 0 4px 18px rgba(0,0,0,0.10);
            letter-spacing: 1px;
        }
        .splash-btn.primary {
            background: #FFD700;
            color: #222;
        }
        .splash-btn.primary:hover {
            background: #fffbe6;
            color: #bfa14a;
            border-color: #bfa14a;
            box-shadow: 0 6px 24px rgba(191,161,74,0.18);
            transform: translateY(-2px) scale(1.04);
        }
        .splash-btn.secondary {
            background: #fffbe6;
            color: #bfa14a;
        }
        .splash-btn.secondary:hover {
            background: #FFD700;
            color: #222;
            border-color: #FFD700;
            box-shadow: 0 6px 24px rgba(191,161,74,0.18);
            transform: translateY(-2px) scale(1.04);
        }
        @media (max-width: 900px) {
            .splash-img { width: 100vw; height: auto; max-height: 40vh; }
            .splash-btn-group { flex-direction: column; gap: 18px; }
        }
        @media (max-width: 600px) {
            .splash-content { padding: 24px 0; }
            .splash-img { max-width: 98vw; max-height: 30vh; margin-bottom: 24px; }
            .splash-btn { font-size: 1.05rem; padding: 12px 16px; min-width: 120px; min-height: 44px; }
        }
    </style>
</head>
<body>
    <div class="splash-content">
        <img src="<?= htmlspecialchars($image_path) ?>" alt="พระบรมฉายาลักษณ์" class="splash-img">
        <div class="splash-btn-group">
            <?php if ($config && isset($config['royal_duties_enabled']) && $config['royal_duties_enabled'] && !empty($config['royal_duties_url'])): ?>
                <a href="<?= htmlspecialchars($config['royal_duties_url']) ?>" class="splash-btn secondary" target="_blank">พระราชกรณียกิจ</a>
            <?php endif; ?>
            <form method="post" style="display:inline; margin:0;">
                <button type="submit" class="splash-btn primary">เข้าสู่เว็บไซต์</button>
            </form>
        </div>
    </div>
</body>
</html> 