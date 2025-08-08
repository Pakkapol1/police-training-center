<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ระบบจัดการ' ?> - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/assets/css/button-links.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }

        * {
            font-family: 'Sarabun', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        /* Top Navigation */
        .top-navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 4px 20px rgba(30, 58, 138, 0.3);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: white !important;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: #fbbf24 !important;
        }

        /* Sidebar */
        .admin-sidebar {
            background: linear-gradient(180deg, #1f2937 0%, #374151 100%);
            min-height: calc(100vh - 76px);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar-header {
            background: rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h5 {
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.875rem 1.25rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #60a5fa 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
            min-height: calc(100vh - 76px);
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .quick-action-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin: 0.5rem;
        }

        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3);
            color: white;
            text-decoration: none;
        }

        .quick-action-btn i {
            margin-right: 8px;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e2e8f0;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            color: var(--dark-color);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #f59e0b 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #ef4444 100%);
            border: none;
        }

        /* Tables */
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .table th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e2e8f0;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Forms */
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: white;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6b7280;
            font-weight: 600;
        }

        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: var(--dark-color);
            font-weight: 600;
        }

        /* User Menu */
        .user-menu {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 0.5rem 1rem;
            color: white;
        }

        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--light-color);
            transform: translateX(5px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                position: fixed;
                left: -100%;
                top: 76px;
                z-index: 1000;
                width: 280px;
                transition: left 0.3s ease;
            }

            .admin-sidebar.show {
                left: 0;
            }

            .main-content {
                padding: 1rem;
            }

            .quick-action-btn {
                width: 100%;
                margin: 0.25rem 0;
                justify-content: center;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Status Badges */
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        .status-pending { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg top-navbar">
        <div class="container-fluid">
            <button class="navbar-toggler d-lg-none" type="button" onclick="toggleSidebar()">
                <i class="fas fa-bars text-white"></i>
            </button>
            
            <a class="navbar-brand" href="/admin?action=dashboard">
                <i class="fas fa-shield-alt"></i>
                ระบบจัดการ ศฝร.ภ.8
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-menu" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <?= htmlspecialchars($_SESSION['admin_name'] ?? 'ผู้ดูแลระบบ') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/" target="_blank">
                            <i class="fas fa-external-link-alt"></i> ดูเว็บไซต์
                        </a></li>
                        <li><a class="dropdown-item" href="/admin?action=profile">
                            <i class="fas fa-user-cog"></i> ตั้งค่าโปรไฟล์
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/admin?action=logout">
                            <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2">
                <div class="admin-sidebar" id="adminSidebar">
                    <div class="sidebar-header">
                        <h5><i class="fas fa-cogs"></i> เมนูจัดการ</h5>
                    </div>
                    
                    <nav class="sidebar-nav">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'dashboard' || empty($_GET['action']) ? 'active' : '' ?>" 
                                   href="/admin?action=dashboard">
                                    <i class="fas fa-tachometer-alt"></i>
                                    แดชบอร์ด
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'news' ? 'active' : '' ?>" 
                                   href="/admin?action=news">
                                    <i class="fas fa-newspaper"></i>
                                    จัดการข่าวสาร
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'announcements' ? 'active' : '' ?>" 
                                   href="/admin?action=announcements">
                                    <i class="fas fa-bullhorn"></i>
                                    จัดการประกาศ
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'documents' ? 'active' : '' ?>" 
                                   href="/admin?action=documents">
                                    <i class="fas fa-file-alt"></i>
                                    จัดการเอกสาร
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'directory' ? 'active' : '' ?>" 
                                   href="/admin?action=directory&type=supervisors">
                                    <i class="fas fa-address-book"></i>
                                    จัดการทำเนียบผู้บังคับบัญชา
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'commanders' ? 'active' : '' ?>" 
                                   href="/admin?action=commanders">
                                    <i class="fas fa-users-cog"></i>
                                    จัดการผู้บังคับบัญชา
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'ranks' ? 'active' : '' ?>" 
                                   href="/admin?action=ranks">
                                    <i class="fas fa-medal"></i>
                                    จัดการยศ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'users' ? 'active' : '' ?>" 
                                   href="/admin?action=users">
                                    <i class="fas fa-users"></i>
                                    จัดการผู้ใช้
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'popup' ? 'active' : '' ?>" href="/admin?action=popup">
                                    <i class="fas fa-bell"></i>
                                    จัดการป๊อบอัพ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'slides' ? 'active' : '' ?>" href="/admin?action=slides">
                                    <i class="fas fa-images"></i>
                                    จัดการสไลด์
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link<?= ($_GET['action'] ?? '') == 'officer-directory' ? ' active' : '' ?>" href="/admin?action=officer-directory">
                                    <i class="fas fa-file-word"></i>
                                    จัดการทำเนียบกำลังสัญญาบัตร
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?= ($_GET['action'] ?? '') == 'enlisted-directory' ? ' active' : '' ?>" href="/admin?action=enlisted-directory">
                                    <i class="fas fa-file-word"></i>
                                    จัดการทำเนียบกำลังพลประทวน
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'settings' ? 'active' : '' ?>" 
                                   href="/admin?action=settings">
                                    <i class="fas fa-cog"></i>
                                    ตั้งค่าระบบ
                                </a>
                            </li>
                            <li class="nav-item">

                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($_GET['action'] ?? '') == 'manageSplash' ? 'active' : '' ?>" href="/admin?action=manageSplash">
                                    <i class="fas fa-bolt"></i>
                                    จัดการ Splash Page
                                </a>
                            </li>
                            
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-10">
                <div class="main-content">
