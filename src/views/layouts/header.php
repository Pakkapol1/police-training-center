<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="image-rendering" content="high-quality">
    <meta name="image-rendering" content="crisp-edges">
    <title><?= $title ?? 'ศูนย์ฝึกอบรมตำรวจภูธรภาค 8' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">


    <style>
        /* Global image quality improvements */
        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            image-rendering: high-quality;
            -webkit-image-rendering: -webkit-optimize-contrast;
            -webkit-image-rendering: crisp-edges;
            -webkit-image-rendering: high-quality;
        }
        
        /* Force hardware acceleration for images */
        .slide-image {
            backface-visibility: hidden;
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
            will-change: transform;
        }
        /* Custom CSS สำหรับ dropdown hover - เฉพาะเดสก์ท็อป */
        @media (min-width: 992px) {
        .navbar-nav .dropdown:hover .dropdown-menu {
                display: block !important;
            margin-top: 0;
            }
            
            /* เดสก์ท็อปใช้ Bootstrap dropdown ปกติ */
            .dropdown-menu {
                position: absolute !important;
                transform: none !important;
                background: white;
                border: 1px solid rgba(0,0,0,0.15);
                margin: 0;
                padding: 0;
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.175);
                border-radius: 8px;
                display: none;
                transition: all 0.3s ease;
            }
            
            .dropdown-item {
                padding: 0.75rem 1.5rem;
                transition: all 0.3s ease;
                color: #212529;
            }
            
            .dropdown-item:hover {
                background-color: #f8f9fa;
                color: #0d6efd;
                transform: translateX(5px);
            }
            
            /* ปิด custom dropdown behavior บนเดสก์ท็อป */
            .navbar-nav .dropdown-menu.show {
                display: none !important;
            }
            
            /* บังคับให้ hover effect ทำงาน */
            .navbar-nav .dropdown:hover .dropdown-menu {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
        }
        
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
            transform: translateX(5px);
        }
        
        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }
        
        /* Enhanced Mobile Navigation */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .navbar-toggler:focus,
        .navbar-toggler:active {
            box-shadow: none;
            outline: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        

        
        /* Mobile responsive navigation */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(71, 27, 28, 0.98);
                position: fixed;
                top: 0;
                right: -100%;
                width: 85%;
                max-width: 350px;
                height: 100vh;
                z-index: 1050;
                transition: right 0.3s ease;
                overflow-y: auto;
                border-radius: 0;
                padding: 0;
                margin-top: 0;
                border: none;
                box-shadow: -5px 0 15px rgba(0,0,0,0.3);
            }
            
            .navbar-collapse.show {
                right: 0;
            }
            
            .navbar-collapse.collapsing {
                right: -100%;
                transition: right 0.35s ease;
            }
            
            /* Mobile menu header */
            .mobile-menu-header {
                background: rgba(0,0,0,0.2);
                padding: 1rem;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: sticky;
                top: 0;
                z-index: 1051;
            }
            
            .mobile-menu-title {
                color: white;
                font-weight: 600;
                margin: 0;
                font-size: 1.1rem;
            }
            
            .mobile-menu-close {
                background: none;
                border: none;
                color: white;
                font-size: 1.5rem;
                padding: 0.25rem;
                cursor: pointer;
                border-radius: 4px;
                transition: background 0.3s ease;
            }
            
            .mobile-menu-close:hover {
                background: rgba(255,255,255,0.1);
            }
            
            .navbar-nav {
                padding: 1rem 0;
                flex-direction: column;
            }
            
            .navbar-nav .nav-link {
                padding: 0.875rem 1.5rem;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                color: rgba(255,255,255,0.9) !important;
                font-weight: 500;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
            }
            
            .navbar-nav .nav-link:hover {
                background: rgba(255,255,255,0.1);
                color: white !important;
                padding-left: 2rem;
            }
            
            .navbar-nav .nav-link i {
                width: 20px;
                margin-right: 12px;
                font-size: 1rem;
            }
            
            /* ปิด Bootstrap dropdown hover - เฉพาะมือถือ */
            @media (max-width: 991.98px) {
                .navbar-nav .dropdown:hover .dropdown-menu {
                    display: none !important;
                }
            }
            
            /* Dropdown items styling สำหรับมือถือ */
            @media (max-width: 991.98px) {
                .navbar-nav .dropdown-item {
                    padding: 0.75rem 2rem !important;
                    color: rgba(255,255,255,0.9) !important;
                    border-bottom: 1px solid rgba(255,255,255,0.1) !important;
                    background: transparent !important;
                    transition: all 0.3s ease !important;
                }
                
                .navbar-nav .dropdown-item:hover {
                    background: rgba(255,255,255,0.1) !important;
                    color: white !important;
                    padding-left: 2.5rem !important;
                }
                
                .navbar-nav .dropdown-item i {
                    width: 20px;
                    margin-right: 12px;
                    font-size: 1rem;
                }
            }
            
            /* Toggle Arrow Animation */
            .dropdown-toggle::after {
                transition: transform 0.3s ease;
                display: inline-block;
                margin-left: auto;
                font-size: 0.8rem;
            }
            
            .dropdown-toggle[aria-expanded="true"]::after {
                transform: rotate(180deg);
            }
            
            .dropdown-toggle.collapsed::after {
                transform: rotate(0deg);
            }
            
            /* Enhance dropdown toggle appearance on mobile */
            .navbar-nav .dropdown-toggle {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }
            
            .navbar-nav .dropdown-toggle i {
                flex-shrink: 0;
            }
            
            .dropdown-item {
                color: rgba(255,255,255,0.8);
                padding: 0.75rem 2.5rem;
                border-bottom: 1px solid rgba(255,255,255,0.05);
                font-size: 0.9rem;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
            }
            
            .dropdown-item:hover {
                background: rgba(255,255,255,0.08);
                color: white;
                transform: none;
                padding-left: 3rem;
            }
            
            .dropdown-item i {
                width: 16px;
                margin-right: 10px;
                font-size: 0.9rem;
            }
            
            /* Overlay */
            .navbar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .navbar-backdrop.show {
                opacity: 1;
                visibility: visible;
            }
        }
        
        @media (max-width: 575.98px) {
            /* Header adjustments */
            .container .row .col-auto img {
                max-height: 60px !important;
                max-width: 60px !important;
                margin-right: 12px !important;
            }
            
            .container .row .col div:first-child {
                font-size: 1.5rem !important;
            }
            
            .container .row .col div:last-child {
                font-size: 0.9rem !important;
            }
            
            /* Mobile menu width */
            .navbar-collapse {
                width: 90%;
                max-width: 320px;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1.25rem;
                font-size: 0.95rem;
            }
            
            .dropdown-item {
                padding: 0.6rem 2rem;
                font-size: 0.85rem;
            }
            
            .mobile-menu-title {
                font-size: 1rem;
            }
        }
        
        /* Smooth scroll prevention when menu is open */
        body.menu-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        /* บังคับให้ dropdown แสดงผล - ใช้ specificity สูงสุด */
        .navbar-nav .dropdown .dropdown-menu.show,
        .navbar-nav .dropdown-menu.show,
        .dropdown-menu.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
            position: static !important;
            transform: none !important;
            float: none !important;
            width: auto !important;
            margin-top: 0 !important;
            background-color: rgba(0,0,0,0.2) !important;
            border: 0 !important;
            box-shadow: none !important;
            z-index: 9999 !important;
        }
        
        /* ปิด Bootstrap dropdown behavior - เฉพาะมือถือ */
        @media (max-width: 991.98px) {
            .dropdown-toggle[data-bs-toggle] {
                pointer-events: auto !important;
            }
            
            /* ปิด Bootstrap dropdown auto behavior */
            .dropdown-menu {
                position: static !important;
                float: none !important;
                width: auto !important;
                margin-top: 0 !important;
                background-color: rgba(0,0,0,0.2) !important;
                border: 0 !important;
                box-shadow: none !important;
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                transition: all 0.3s ease !important;
            }
        }

            /* บังคับให้ dropdown แสดงผลบนมือถือ - ใช้ specificity สูง */
            @media (max-width: 991.98px) {
                .navbar .navbar-nav .dropdown .dropdown-menu.show,
                .navbar-nav .dropdown .dropdown-menu.show,
                .dropdown .dropdown-menu.show,
                .dropdown-menu.show {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                    height: auto !important;
                    max-height: none !important;
                    overflow: visible !important;
                    position: static !important;
                    transform: none !important;
                    float: none !important;
                    width: auto !important;
                    margin-top: 0 !important;
                    background-color: rgba(0,0,0,0.2) !important;
                    border: 0 !important;
                    box-shadow: none !important;
                    z-index: 9999 !important;
                    pointer-events: auto !important;
                }
                
                /* ปิด desktop hover effect บนมือถือ */
                .navbar-nav .dropdown:hover .dropdown-menu {
                    display: none !important;
                }
                
                /* บังคับให้ dropdown items แสดงผล */
                .navbar .navbar-nav .dropdown .dropdown-menu.show .dropdown-item,
                .navbar-nav .dropdown .dropdown-menu.show .dropdown-item,
                .dropdown .dropdown-menu.show .dropdown-item,
                .dropdown-menu.show .dropdown-item {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                    color: rgba(255,255,255,0.8) !important;
                    padding: 0.75rem 2.5rem !important;
                    border-bottom: 1px solid rgba(255,255,255,0.05) !important;
                    font-size: 0.9rem !important;
                    transition: all 0.3s ease !important;
                    background: transparent !important;
                }
                
                .navbar .navbar-nav .dropdown .dropdown-menu.show .dropdown-item:hover,
                .navbar-nav .dropdown .dropdown-menu.show .dropdown-item:hover,
                .dropdown .dropdown-menu.show .dropdown-item:hover,
                .dropdown-menu.show .dropdown-item:hover {
                    background: rgba(255,255,255,0.08) !important;
                    color: white !important;
                    padding-left: 3rem !important;
                }
            }
        /* บังคับให้ dropdown แสดงผลบนเดสก์ท็อป */
        @media (min-width: 992px) {
            .navbar-nav .dropdown:hover .dropdown-menu,
            .dropdown:hover .dropdown-menu {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                z-index: 1000 !important;
            }
        }

        /* Dropdown styling สำหรับเดสก์ท็อป */
        @media (min-width: 992px) {
            .dropdown-menu {
                background: white !important;
                border: 1px solid rgba(0,0,0,0.15) !important;
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.175) !important;
                border-radius: 8px !important;
                min-width: 200px !important;
            }
            
            .dropdown-item {
                color: #212529 !important;
                padding: 0.75rem 1.5rem !important;
            }
            
            .dropdown-item:hover {
                background-color: #f8f9fa !important;
                color: #0d6efd !important;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="py-3" style="background: #fff; color: #1e3a8a; border-bottom: 1.5px solid #e5e7eb;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto d-flex align-items-center" style="padding-right:0;">
                    <a href="/?page=home" style="text-decoration: none; display: flex; align-items: center;">
                    <img src="/assets/img/stic.webp" alt="โลโก้หน่วยงาน" class="img-fluid" style="max-height: 80px; max-width: 80px; margin-right: 18px;">
                    </a>
                </div>
                <div class="col ps-0">
                    <a href="/?page=home" style="text-decoration: none; color: inherit;">
                        <div style="font-size:2.1rem; font-weight:700; color:#222; line-height:1.1; cursor: pointer; transition: color 0.3s ease;" onmouseover="this.style.color='#1e3a8a'" onmouseout="this.style.color='#222'">ธรรมชาติหนูน้อย</div>
                        <div style="font-size:1.15rem; color:#1e3a8a; font-weight:500; letter-spacing:1px; cursor: pointer; transition: color 0.3s ease;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#1e3a8a'">little nature</div>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation with Dropdown -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #471b1c;">
        <div class="container">
            <button class="navbar-toggler" type="button" onclick="toggleMobileMenu()" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Mobile Menu Backdrop -->
            <div class="navbar-backdrop" id="navbarBackdrop" onclick="closeMobileMenu()"></div>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Mobile Menu Header -->
                <div class="mobile-menu-header d-lg-none">
                    <h6 class="mobile-menu-title">
                        <i class="fas fa-bars"></i> เมนูหลัก
                    </h6>
                    <button class="mobile-menu-close" onclick="closeMobileMenu()" aria-label="Close menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <ul class="navbar-nav me-auto">
                    <!-- หน้าหลัก -->
                    <li class="nav-item">
                        <a class="nav-link" href="/?page=home" onclick="handleMobileNavClick()">
                            <i class="fas fa-home"></i> หน้าหลัก
                        </a>
                    </li>

                    <!-- เกี่ยวกับเรา - Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle collapsed" href="#" role="button" aria-expanded="false">
                            <i class="fas fa-info-circle"></i> เกี่ยวกับเรา
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/?page=history" onclick="handleMobileNavClick()">
                                <i class="fas fa-history"></i> ประวัติ ศฝร.ภ.
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=org-structure" onclick="handleMobileNavClick()">
                                <i class="fas fa-sitemap"></i> โครงสร้าง ศฝร.
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=mission" onclick="handleMobileNavClick()">
                                <i class="fas fa-clipboard-list"></i> ภารกิจ ศฝร.
                            </a></li>
                        </ul>
                    </li>

                    <!-- เมนูหลัก - Dropdown -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle collapsed" href="#" role="button" aria-expanded="false">
        <i class="fas fa-bars"></i> เมนูหลัก
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="/?page=commanders" onclick="handleMobileNavClick()">
            <i class="fas fa-star"></i> ผู้บังคับบัญชา
        </a></li>
        <li><a class="dropdown-item" href="/?page=directory-officers" onclick="handleMobileNavClick()">
            <i class="fas fa-users-cog"></i> ทำเนียบกำลังพลสัญญาบัตร
        </a></li>
        <li><a class="dropdown-item" href="/?page=directory-enlisted" onclick="handleMobileNavClick()">
            <i class="fas fa-users"></i> ทำเนียบกำลังพลประทวน
        </a></li>
        <li><a class="dropdown-item" href="/?page=directory-commanders" onclick="handleMobileNavClick()">
            <i class="fas fa-user-tie"></i> ทำเนียบผู้บังคับบัญชา
        </a></li>
        <li><a class="dropdown-item" href="/?page=vision-philosophy" onclick="handleMobileNavClick()">
            <i class="fas fa-eye"></i> วิสัยทัศน์ ปรัชญา ปณิธาน
        </a></li>
        <li><a class="dropdown-item" href="/?page=location-map" onclick="handleMobileNavClick()">
            <i class="fas fa-map-marker-alt"></i> แผนที่ตั้ง ศฝร.ภ.8
        </a></li>
        <li><a class="dropdown-item" href="/?page=webmaster" onclick="handleMobileNavClick()">
            <i class="fas fa-user-cog"></i> ผู้ดูแลเว็บไซต์
        </a></li>
    </ul>
</li>
                    
                    <!-- ข่าวสาร - Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle collapsed" href="#" role="button" aria-expanded="false">
                            <i class="fas fa-newspaper"></i> ข่าวสาร
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/?page=news" onclick="handleMobileNavClick()">
                                <i class="fas fa-list"></i> ข่าวสารทั้งหมด
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=announcements" onclick="handleMobileNavClick()">
                                <i class="fas fa-bullhorn"></i> ประกาศ
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=news&category=activity" onclick="handleMobileNavClick()">
                                <i class="fas fa-calendar-check"></i> กิจกรรม
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=news&category=recruitment" onclick="handleMobileNavClick()">
                                <i class="fas fa-user-plus"></i> การรับสมัคร น.ส.ต.
                            </a></li>
                        </ul>
                    </li>
                    

                    
                    <!-- หลักสูตรฝึกอบรม - Dropdown -->

                    
                    <!-- บริการ - Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle collapsed" href="#" role="button" aria-expanded="false">
                            <i class="fas fa-concierge-bell"></i> บริการ
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/?page=documents" onclick="handleMobileNavClick()">
                                <i class="fas fa-file-download"></i> เอกสารดาวน์โหลด
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=gallery" onclick="handleMobileNavClick()">
                                <i class="fas fa-images"></i> ภาพกิจกรรม
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=facilities" onclick="handleMobileNavClick()">
                                <i class="fas fa-building"></i> สิ่งอำนวยความสะดวก
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=library" onclick="handleMobileNavClick()">
                                <i class="fas fa-book"></i> ห้องสมุด
                            </a></li>
                        </ul>
                    </li>
                    
                    <!-- ติดต่อเรา - Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle collapsed" href="#" role="button" aria-expanded="false">
                            <i class="fas fa-phone"></i> ติดต่อเรา
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/?page=contact" onclick="handleMobileNavClick()">
                                <i class="fas fa-envelope"></i> ส่งข้อความถึงเรา
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=location" onclick="handleMobileNavClick()">
                                <i class="fas fa-map-marker-alt"></i> ที่ตั้งและแผนที่
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=directions" onclick="handleMobileNavClick()">
                                <i class="fas fa-route"></i> การเดินทาง
                            </a></li>
                            <li><a class="dropdown-item" href="tel:077-123456" onclick="handleMobileNavClick()">
                                <i class="fas fa-phone-alt"></i> โทรศัพท์ 077-123456
                            </a></li>
                        </ul>
                    </li>
                </ul>
                
                <!-- เมนูด้านขวา -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle collapsed" href="#" role="button" aria-expanded="false">
                            <i class="fas fa-cog"></i> ระบบ
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/admin?action=dashboard" onclick="handleMobileNavClick()">
                                <i class="fas fa-tachometer-alt"></i> ระบบจัดการ
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=search" onclick="handleMobileNavClick()">
                                <i class="fas fa-search"></i> ค้นหา
                            </a></li>
                            <li><a class="dropdown-item" href="/?page=sitemap" onclick="handleMobileNavClick()">
                                <i class="fas fa-sitemap"></i> แผนผังเว็บไซต์
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- JavaScript สำหรับ Enhanced Mobile Navigation -->
    <script>
        // ตัวแปรสำหรับจัดการ mobile menu
        let isMobileMenuOpen = false;
        
        // ฟังก์ชันเปิด/ปิด mobile menu
        function toggleMobileMenu() {
            const navbarCollapse = document.getElementById('navbarNav');
            const backdrop = document.getElementById('navbarBackdrop');
            const body = document.body;
            
            if (!isMobileMenuOpen) {
                navbarCollapse.classList.add('show');
                backdrop.classList.add('show');
                body.classList.add('menu-open');
                isMobileMenuOpen = true;
                
                // เริ่มต้น dropdown ทั้งหมดในสถานะปิด
                const dropdownToggleLinks = document.querySelectorAll('.navbar-nav .dropdown-toggle');
                const dropdownMenus = document.querySelectorAll('.navbar-nav .dropdown-menu');
                
                dropdownToggleLinks.forEach(function(toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.classList.add('collapsed');
                });
                
                dropdownMenus.forEach(function(menu) {
                    menu.classList.remove('show');
                    menu.style.display = 'none';
                    menu.style.visibility = 'hidden';
                    menu.style.opacity = '0';
                });
            } else {
                closeMobileMenu();
            }
        }
        
        // ฟังก์ชันปิด mobile menu
        function closeMobileMenu() {
            const navbarCollapse = document.getElementById('navbarNav');
            const backdrop = document.getElementById('navbarBackdrop');
            const body = document.body;
            
            navbarCollapse.classList.remove('show');
            backdrop.classList.remove('show');
            body.classList.remove('menu-open');
            isMobileMenuOpen = false;
            
            // ปิด dropdown ทั้งหมด
            const dropdownToggleLinks = document.querySelectorAll('.navbar-nav .dropdown-toggle');
            const dropdownMenus = document.querySelectorAll('.navbar-nav .dropdown-menu');
            
            dropdownToggleLinks.forEach(function(toggle) {
                toggle.setAttribute('aria-expanded', 'false');
                toggle.classList.add('collapsed');
            });
            
            dropdownMenus.forEach(function(menu) {
                menu.classList.remove('show');
                menu.style.display = 'none';
                menu.style.visibility = 'hidden';
                menu.style.opacity = '0';
                menu.style.height = 'auto';
                menu.style.maxHeight = 'none';
                menu.style.overflow = 'visible';
                menu.style.position = 'static';
                menu.style.transform = 'none';
                menu.style.float = 'none';
                menu.style.width = 'auto';
                menu.style.marginTop = '0';
                menu.style.backgroundColor = 'rgba(0,0,0,0.2)';
                menu.style.border = '0';
                menu.style.boxShadow = 'none';
                menu.style.zIndex = '9999';
            });
        }
        
        // ฟังก์ชันจัดการเมื่อคลิก nav link บนมือถือ
        function handleMobileNavClick() {
            // ตรวจสอบว่าอยู่บนมือถือหรือไม่
            if (window.innerWidth <= 991) {
                // ปิดเฉพาะเมนูหลัก ไม่ปิด dropdown
                const navbarCollapse = document.getElementById('navbarNav');
                const backdrop = document.getElementById('navbarBackdrop');
                const body = document.body;
                
                navbarCollapse.classList.remove('show');
                backdrop.classList.remove('show');
                body.classList.remove('menu-open');
                isMobileMenuOpen = false;
            }
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // ตรวจสอบ dropdown elements
            const allDropdownToggles = document.querySelectorAll('.navbar-nav .dropdown-toggle');
            const allDropdownMenus = document.querySelectorAll('.navbar-nav .dropdown-menu');
            
            console.log('Found dropdown toggles:', allDropdownToggles.length);
            console.log('Found dropdown menus:', allDropdownMenus.length);
            
            allDropdownToggles.forEach(function(toggle, index) {
                console.log(`Toggle ${index}:`, toggle.textContent.trim());
                const dropdown = toggle.closest('.dropdown');
                const menu = dropdown.querySelector('.dropdown-menu');
                console.log(`Menu ${index} found:`, !!menu);
                if (menu) {
                    console.log(`Menu ${index} classes:`, menu.className);
                    console.log(`Menu ${index} display:`, menu.style.display);
                }
            });
            
            // เริ่มต้น dropdown ทั้งหมดในสถานะปิด
            allDropdownToggles.forEach(function(toggle) {
                toggle.setAttribute('aria-expanded', 'false');
                toggle.classList.add('collapsed');
            });
            
            allDropdownMenus.forEach(function(menu) {
                menu.classList.remove('show');
                menu.style.display = 'none';
                menu.style.visibility = 'hidden';
                menu.style.opacity = '0';
            });
            
            // จัดการ data-bs-toggle attribute เริ่มต้น
            const initialDropdownToggles = document.querySelectorAll('.navbar-nav .dropdown-toggle');
            initialDropdownToggles.forEach(function(toggleLink) {
                // ไม่ต้องจัดการ data-bs-toggle เพราะเดสก์ท็อปใช้ hover effect
            });
            
            // ปิดเมนูเมื่อปรับขนาดหน้าจอ
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991 && isMobileMenuOpen) {
                    closeMobileMenu();
                }
                
                // ไม่ต้องจัดการ data-bs-toggle เพราะเดสก์ท็อปใช้ hover effect
            });
            
            // ESC key to close menu
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isMobileMenuOpen) {
                    closeMobileMenu();
                }
            });
            
            // Custom dropdown system for mobile
            const dropdownToggleLinks = document.querySelectorAll('.navbar-nav .dropdown-toggle');
            
            dropdownToggleLinks.forEach(function(toggleLink) {
                // เริ่มต้นในสถานะปิด
                toggleLink.setAttribute('aria-expanded', 'false');
                toggleLink.classList.add('collapsed');
                
                toggleLink.addEventListener('click', function(e) {
                    // On mobile, handle custom dropdown
                    if (window.innerWidth <= 991) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        
                        const dropdown = this.closest('.dropdown');
                        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                        const isExpanded = this.getAttribute('aria-expanded') === 'true';
                        
                        console.log('Mobile dropdown clicked:', this.textContent.trim());
                        console.log('Current state:', isExpanded);
                        console.log('Dropdown menu found:', !!dropdownMenu);
                        
                        // Toggle current dropdown
                        if (dropdownMenu) {
                            if (isExpanded) {
                                // Close current dropdown
                                dropdownMenu.classList.remove('show');
                                dropdownMenu.style.display = 'none';
                                dropdownMenu.style.visibility = 'hidden';
                                dropdownMenu.style.opacity = '0';
                                this.setAttribute('aria-expanded', 'false');
                                this.classList.add('collapsed');
                                console.log('Closing mobile dropdown');
                            } else {
                                // Close other dropdowns first
                                dropdownToggleLinks.forEach(function(otherToggle) {
                                    if (otherToggle !== toggleLink) {
                                        const otherDropdown = otherToggle.closest('.dropdown');
                                        const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                                        if (otherMenu) {
                                            otherMenu.classList.remove('show');
                                            otherMenu.style.display = 'none';
                                            otherMenu.style.visibility = 'hidden';
                                            otherMenu.style.opacity = '0';
                                            otherToggle.setAttribute('aria-expanded', 'false');
                                            otherToggle.classList.add('collapsed');
                                        }
                                    }
                                });
                                
                                // Open current dropdown
                                dropdownMenu.classList.add('show');
                                
                                // ใช้ setTimeout เพื่อให้ CSS มีเวลาทำงาน
                                setTimeout(() => {
                                    dropdownMenu.style.display = 'block';
                                    dropdownMenu.style.visibility = 'visible';
                                    dropdownMenu.style.opacity = '1';
                                    dropdownMenu.style.height = 'auto';
                                    dropdownMenu.style.maxHeight = 'none';
                                    dropdownMenu.style.overflow = 'visible';
                                    dropdownMenu.style.position = 'static';
                                    dropdownMenu.style.transform = 'none';
                                    dropdownMenu.style.float = 'none';
                                    dropdownMenu.style.width = 'auto';
                                    dropdownMenu.style.marginTop = '0';
                                    dropdownMenu.style.backgroundColor = 'rgba(0,0,0,0.2)';
                                    dropdownMenu.style.border = '0';
                                    dropdownMenu.style.boxShadow = 'none';
                                    dropdownMenu.style.zIndex = '9999';
                                    dropdownMenu.style.pointerEvents = 'auto';
                                    
                                    // บังคับให้ dropdown items แสดงผล
                                    const dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');
                                    dropdownItems.forEach(function(item) {
                                        item.style.display = 'block';
                                        item.style.visibility = 'visible';
                                        item.style.opacity = '1';
                                        item.style.color = 'rgba(255,255,255,0.8)';
                                        item.style.padding = '0.75rem 2.5rem';
                                        item.style.borderBottom = '1px solid rgba(255,255,255,0.05)';
                                        item.style.fontSize = '0.9rem';
                                        item.style.transition = 'all 0.3s ease';
                                        item.style.background = 'transparent';
                                    });
                                    
                                    this.setAttribute('aria-expanded', 'true');
                                    this.classList.remove('collapsed');
                                    console.log('Opening mobile dropdown');
                                    console.log('Menu classes after opening:', dropdownMenu.className);
                                    console.log('Menu display after opening:', dropdownMenu.style.display);
                                    console.log('Menu computed display:', window.getComputedStyle(dropdownMenu).display);
                                    console.log('Dropdown items count:', dropdownItems.length);
                                }, 10);
                            }
                        } else {
                            console.log('No dropdown menu found!');
                        }
                        
                        return false;
                    }
                });
            });
            
            // Prevent dropdown menu clicks from closing on mobile
            const dropdownMenus = document.querySelectorAll('.dropdown-menu');
            dropdownMenus.forEach(function(menu) {
                menu.addEventListener('click', function(e) {
                    if (window.innerWidth <= 991) {
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                    }
                });
                
                // ป้องกันการปิด dropdown เมื่อคลิกในเมนู
                menu.addEventListener('mousedown', function(e) {
                    if (window.innerWidth <= 991) {
                        e.stopPropagation();
                    }
                });
                
                menu.addEventListener('touchstart', function(e) {
                    if (window.innerWidth <= 991) {
                        e.stopPropagation();
                    }
                });
            });

            // จัดการ dropdown items บนมือถือ
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(function(item) {
                item.addEventListener('click', function(e) {
                    if (window.innerWidth <= 991) {
                        // ไม่ต้องปิด dropdown เมื่อคลิกลิงก์
                        // ให้ handleMobileNavClick จัดการเอง
                    }
                });
            });

            // Backdrop click handling
            const backdrop = document.getElementById('navbarBackdrop');
            if (backdrop) {
                backdrop.addEventListener('click', function(e) {
                    if (window.innerWidth <= 991) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeMobileMenu();
                    }
                });
            }

            // Desktop dropdown hover effect
            const dropdowns = document.querySelectorAll('.navbar-nav .dropdown');
            
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('mouseenter', function() {
                    // Only on desktop
                    if (window.innerWidth > 991) {
                    const dropdownMenu = this.querySelector('.dropdown-menu');
                    if (dropdownMenu) {
                            dropdownMenu.style.display = 'block';
                            dropdownMenu.style.visibility = 'visible';
                            dropdownMenu.style.opacity = '1';
                        }
                    }
                });
                
                dropdown.addEventListener('mouseleave', function() {
                    // Only on desktop
                    if (window.innerWidth > 991) {
                    const dropdownMenu = this.querySelector('.dropdown-menu');
                    if (dropdownMenu) {
                            dropdownMenu.style.display = 'none';
                            dropdownMenu.style.visibility = 'hidden';
                            dropdownMenu.style.opacity = '0';
                        }
                    }
                });
            });

            // ตรวจสอบและแก้ไข CSS ที่ override dropdown
            function forceDropdownDisplay() {
                if (window.innerWidth <= 991) {
                    const dropdownMenus = document.querySelectorAll('.dropdown-menu.show');
                    dropdownMenus.forEach(function(menu) {
                        // บังคับให้แสดงผล
                        menu.style.setProperty('display', 'block', 'important');
                        menu.style.setProperty('visibility', 'visible', 'important');
                        menu.style.setProperty('opacity', '1', 'important');
                        menu.style.setProperty('height', 'auto', 'important');
                        menu.style.setProperty('max-height', 'none', 'important');
                        menu.style.setProperty('overflow', 'visible', 'important');
                        menu.style.setProperty('position', 'static', 'important');
                        menu.style.setProperty('transform', 'none', 'important');
                        menu.style.setProperty('float', 'none', 'important');
                        menu.style.setProperty('width', 'auto', 'important');
                        menu.style.setProperty('margin-top', '0', 'important');
                        menu.style.setProperty('background-color', 'rgba(0,0,0,0.2)', 'important');
                        menu.style.setProperty('border', '0', 'important');
                        menu.style.setProperty('box-shadow', 'none', 'important');
                        menu.style.setProperty('z-index', '9999', 'important');
                        menu.style.setProperty('pointer-events', 'auto', 'important');
                        
                        // บังคับให้ dropdown items แสดงผล
                        const dropdownItems = menu.querySelectorAll('.dropdown-item');
                        dropdownItems.forEach(function(item) {
                            item.style.setProperty('display', 'block', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                            item.style.setProperty('color', 'rgba(255,255,255,0.8)', 'important');
                            item.style.setProperty('padding', '0.75rem 2.5rem', 'important');
                            item.style.setProperty('border-bottom', '1px solid rgba(255,255,255,0.05)', 'important');
                            item.style.setProperty('font-size', '0.9rem', 'important');
                            item.style.setProperty('transition', 'all 0.3s ease', 'important');
                            item.style.setProperty('background', 'transparent', 'important');
                        });
                    });
                }
            }
            
            // เรียกใช้ฟังก์ชันทุก 100ms บนมือถือ
            if (window.innerWidth <= 991) {
                setInterval(forceDropdownDisplay, 100);
            }
        });
    </script>
