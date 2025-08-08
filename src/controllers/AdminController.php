<?php
require_once SRC_PATH . '/models/AdminModel.php';
require_once SRC_PATH . '/models/NewsModel.php';
require_once SRC_PATH . '/models/AnnouncementModel.php';
require_once SRC_PATH . '/models/DocumentModel.php';
require_once SRC_PATH . '/models/CommanderModel.php';

require_once SRC_PATH . '/models/RankModel.php';
require_once SRC_PATH . '/models/PopupModel.php';
require_once SRC_PATH . '/models/OfficerDirectoryModel.php';
require_once SRC_PATH . '/models/DirectoryModel.php';
require_once SRC_PATH . '/models/SplashConfigModel.php';
require_once SRC_PATH . '/models/ActivityLogModel.php';
require_once SRC_PATH . '/models/SlideModel.php';



class AdminController {
    private $adminModel;
    private $newsModel;
    private $announcementModel;
    private $documentModel;
    private $commanderModel;
    private $positionModel;
    private $rankModel;
    private $popupModel;
    private $officerDirectoryModel;
    private $directoryModel;
    private $activityLogModel;
    private $slideModel;


    
    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        global $pdo;    
        if (!$pdo) {
            die('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
        }
        
        // ตั้งค่า headers สำหรับ UTF-8
        header('Content-Type: text/html; charset=utf-8');
        
        $this->adminModel = new AdminModel();
        $this->newsModel = new NewsModel();
        $this->announcementModel = new AnnouncementModel();
        $this->documentModel = new DocumentModel();
        $this->commanderModel = new CommanderModel();

        $this->rankModel = new RankModel();
        $this->popupModel = new PopupModel();
        $this->officerDirectoryModel = new OfficerDirectoryModel();
        $this->directoryModel = new DirectoryModel();

        $this->activityLogModel = new ActivityLogModel();
        $this->slideModel = new SlideModel();

    }
    
    public function login() {
        $error = null;
        $title = 'เข้าสู่ระบบ';
        
        if ($_POST) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $admin = $this->adminModel->authenticate($username, $password);
            
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_role'] = $admin['role'];
                
                // บันทึกกิจกรรมเข้าสู่ระบบ
                $this->activityLogModel->logActivity([
                    'action_type' => 'login',
                    'module' => 'auth',
                    'description' => 'เข้าสู่ระบบ',
                    'user_id' => $admin['id'],
                    'user_name' => $admin['full_name']
                ]);
                
                header('Location: /admin?action=dashboard');
                exit;
            } else {
                $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            }
        }
        
        $this->loadView('admin/login', compact('error', 'title'));
    }
    
    public function logout() {
        // บันทึกกิจกรรมออกจากระบบ
        if (isset($_SESSION['admin_id'])) {
            $this->activityLogModel->logActivity([
                'action_type' => 'logout',
                'module' => 'auth',
                'description' => 'ออกจากระบบ',
                'user_id' => $_SESSION['admin_id'],
                'user_name' => $_SESSION['admin_name'] ?? ''
            ]);
        }
        
        session_destroy();
        header('Location: /admin?action=login');
        exit;
    }
    
    public function dashboard() {
        $stats = [
            'total_news' => $this->newsModel->getTotalNews(),
            'total_documents' => $this->documentModel->getTotalDocuments()
        ];
        
        $recentNews = $this->newsModel->getLatestNews(5);
        $recentActivities = $this->activityLogModel->getRecentActivities(8);
        $title = 'แดชบอร์ด';
        
        $this->loadView('admin/dashboard', compact('stats', 'recentNews', 'recentActivities', 'title'));
    }

    /**
     * แปลงเวลาเป็นรูปแบบ "เมื่อ X ที่แล้ว"
     */
    private function getTimeAgo($datetime) {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) {
            return 'เมื่อสักครู่';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return "เมื่อ {$minutes} นาทีที่แล้ว";
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return "เมื่อ {$hours} ชั่วโมงที่แล้ว";
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return "เมื่อ {$days} วันที่แล้ว";
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return "เมื่อ {$months} เดือนที่แล้ว";
        } else {
            $years = floor($diff / 31536000);
            return "เมื่อ {$years} ปีที่แล้ว";
        }
    }

    /**
     * หน้า Activity Log
     */
    public function activityLog() {
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 20;
        
        // รับ filters
        $filters = [
            'module' => $_GET['module'] ?? '',
            'action_type' => $_GET['action_type'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        
        // ดึงข้อมูลกิจกรรม
        $activities = $this->activityLogModel->getActivitiesWithFilters($filters, $page, $perPage);
        $totalActivities = $this->activityLogModel->getTotalActivitiesWithFilters($filters);
        $totalPages = ceil($totalActivities / $perPage);
        
        // สถิติ
        $uniqueUsers = $this->activityLogModel->getUniqueUsersCount();
        $todayActivities = $this->activityLogModel->getTodayActivitiesCount();
        $thisWeekActivities = $this->activityLogModel->getThisWeekActivitiesCount();
        
        // ส่งออกข้อมูล
        if (isset($_GET['export']) && $_GET['export'] == '1') {
            $this->exportActivities($filters);
            exit;
        }
        
        $title = 'ประวัติกิจกรรม';
        
        // สร้าง arrays สำหรับชื่อโมดูลและประเภทกิจกรรม
        $moduleNames = [
            'news' => 'ข่าวสาร',
            'commanders' => 'ผู้บังคับบัญชา',
            'documents' => 'เอกสาร',
            'auth' => 'การเข้าสู่ระบบ',
            'splash' => 'Splash Page'
        ];
        
        $actionNames = [
            'create' => 'สร้าง',
            'update' => 'แก้ไข',
            'delete' => 'ลบ',
            'login' => 'เข้าสู่ระบบ',
            'logout' => 'ออกจากระบบ',
            'upload' => 'อัปโหลด',
            'download' => 'ดาวน์โหลด',
            'approve' => 'อนุมัติ',
            'reject' => 'ปฏิเสธ'
        ];
        
        $this->loadView('admin/activity-log', compact(
            'activities', 'filters', 'page', 'totalPages', 'totalActivities',
            'uniqueUsers', 'todayActivities', 'thisWeekActivities', 'title',
            'moduleNames', 'actionNames'
        ));
    }

    /**
     * ส่งออกข้อมูลกิจกรรมเป็น CSV
     */
    private function exportActivities($filters) {
        $activities = $this->activityLogModel->getActivitiesWithFilters($filters, 1, 10000); // ดึงทั้งหมด
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="activity-log-' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // เพิ่ม BOM สำหรับ UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, [
            'วันที่',
            'เวลา',
            'ประเภท',
            'โมดูล',
            'รายละเอียด',
            'ผู้ใช้งาน',
            'IP Address'
        ]);
        
        // ข้อมูล
        foreach ($activities as $activity) {
            fputcsv($output, [
                date('d/m/Y', strtotime($activity['created_at'])),
                date('H:i:s', strtotime($activity['created_at'])),
                $this->getActionTypeName($activity['action_type']),
                $this->getModuleName($activity['module']),
                $activity['description'],
                $activity['user_name'] ?? '-',
                $activity['ip_address'] ?? '-'
            ]);
        }
        
        fclose($output);
    }

    /**
     * แปลงชื่อโมดูลเป็นภาษาไทย
     */
    private function getModuleName($module) {
        $moduleNames = [
            'news' => 'ข่าวสาร',
            'courses' => 'หลักสูตร',
            'commanders' => 'ผู้บังคับบัญชา',
            'documents' => 'เอกสาร',
            'registrations' => 'การลงทะเบียน',
            'auth' => 'การเข้าสู่ระบบ',
            'splash' => 'Splash Page'
        ];
        
        return $moduleNames[$module] ?? $module;
    }

    /**
     * แปลงชื่อประเภทกิจกรรมเป็นภาษาไทย
     */
    private function getActionTypeName($actionType) {
        $actionNames = [
            'create' => 'สร้าง',
            'update' => 'แก้ไข',
            'delete' => 'ลบ',
            'login' => 'เข้าสู่ระบบ',
            'logout' => 'ออกจากระบบ',
            'upload' => 'อัปโหลด',
            'download' => 'ดาวน์โหลด',
            'approve' => 'อนุมัติ',
            'reject' => 'ปฏิเสธ'
        ];
        
        return $actionNames[$actionType] ?? $actionType;
    }
    
    public function manageNews() {
        $action = $_GET['sub_action'] ?? 'list';
        
        switch($action) {
            case 'add':
                $this->addNews();
                break;
            case 'edit':
                $this->editNews();
                break;
            case 'delete':
                $this->deleteNews();
                break;
            case 'delete_image':
                $this->deleteNewsImage();
                break;
            default:
                $this->listNews();
        }
    }
    
    public function manageAnnouncements() {
        $action = $_GET['sub_action'] ?? 'list';
        
        switch($action) {
            case 'add':
                $this->addAnnouncement();
                break;
            case 'edit':
                $this->editAnnouncement();
                break;
            case 'delete':
                $this->deleteAnnouncement();
                break;
            case 'delete_image':
                $this->deleteAnnouncementImage();
                break;
            default:
                $this->listAnnouncements();
        }
    }
    
    private function listNews() {
        $perPage = 10;
        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? 'all';
        $newsList = $this->newsModel->getAllNews($page, $perPage, $search, $status);
        $totalNews = $this->newsModel->getTotalNews($search, $status);
        $totalPages = ceil($totalNews / $perPage);
        $this->loadView('admin/news/list', compact('newsList', 'totalNews', 'totalPages', 'page', 'search', 'status'));
    }
    
    private function addNews() {
        $error = null;
        
        if ($_POST) {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'status' => $_POST['status'] ?? 'draft',
                'created_by' => $_SESSION['admin_id']
            ];
            
            if (isset($_POST['category']) && is_array($_POST['category'])) {
                $data['category'] = implode(',', $_POST['category']);
            }
            
            if (empty($data['title'])) {
                $error = 'กรุณากรอกหัวข้อข่าว';
            } elseif (empty($data['content'])) {
                $error = 'กรุณากรอกเนื้อหาข่าว';
            } else {
                // DEBUG: log the files array
                error_log('DEBUG $_FILES[image]: ' . print_r($_FILES['image'], true));
                if (!$error) {
                    if ($this->newsModel->createNews($data)) {
                        $newsId = $this->newsModel->getLastInsertId();
                        if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
                            foreach ($_FILES['image']['name'] as $i => $name) {
                                if ($_FILES['image']['error'][$i] === 0) {
                                    $file = [
                                        'name' => $_FILES['image']['name'][$i],
                                        'type' => $_FILES['image']['type'][$i],
                                        'tmp_name' => $_FILES['image']['tmp_name'][$i],
                                        'error' => $_FILES['image']['error'][$i],
                                        'size' => $_FILES['image']['size'][$i]
                                    ];
                                    $uploadResult = $this->uploadImage($file, 'news');
                                    if ($uploadResult['success']) {
                                        $this->newsModel->addNewsImage($newsId, $uploadResult['path']);
                                    }
                                }
                            }
                        }
                        
                        // บันทึกกิจกรรม
                        $this->activityLogModel->logActivity([
                            'action_type' => 'create',
                            'module' => 'news',
                            'description' => ActivityLogModel::createDescription('create', 'news', ['title' => $data['title']]),
                            'user_id' => $_SESSION['admin_id'],
                            'user_name' => $_SESSION['admin_name'],
                            'related_id' => $newsId,
                            'related_table' => 'news'
                        ]);
                        
                        header('Location: /admin?action=news&message=added');
                        exit;
                    } else {
                        $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                    }
                }
            }
        }
        
        $this->loadView('admin/news/add', compact('error'));
    }
    
    private function editNews() {
        $id = $_GET['id'] ?? 0;
        $news = $this->newsModel->getNewsById($id);
        $error = null;
        
        if (!$news) {
            header('Location: /admin?action=news&error=not_found');
            exit;
        }
        
        if ($_POST) {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'status' => $_POST['status'] ?? 'draft'
            ];
            
            if (isset($_POST['category']) && is_array($_POST['category'])) {
                $data['category'] = implode(',', $_POST['category']);
            }
            
            // ตรวจสอบข้อมูลที่จำเป็น
            if (empty($data['title'])) {
                $error = 'กรุณากรอกหัวข้อข่าว';
            } elseif (empty($data['content'])) {
                $error = 'กรุณากรอกเนื้อหาข่าว';
            } else {
                // DEBUG: log the files array
                error_log('DEBUG $_FILES[image]: ' . print_r($_FILES['image'], true));
                
                if (!$error) {
                    if ($this->newsModel->updateNews($id, $data)) {
                        // จัดการไฟล์รูปภาพหลายไฟล์
                        if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
                            foreach ($_FILES['image']['name'] as $i => $name) {
                                if ($_FILES['image']['error'][$i] === 0) {
                                    $file = [
                                        'name' => $_FILES['image']['name'][$i],
                                        'type' => $_FILES['image']['type'][$i],
                                        'tmp_name' => $_FILES['image']['tmp_name'][$i],
                                        'error' => $_FILES['image']['error'][$i],
                                        'size' => $_FILES['image']['size'][$i]
                                    ];
                                    $uploadResult = $this->uploadImage($file, 'news');
                                    if ($uploadResult['success']) {
                                        $this->newsModel->addNewsImage($id, $uploadResult['path']);
                                    } else {
                                        error_log('Upload failed for file ' . $name . ': ' . $uploadResult['error']);
                                    }
                                }
                            }
                        }
                        header('Location: /admin?action=news&message=updated');
                        exit;
                    } else {
                        $error = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
                    }
                }
            }
        }
        
        $this->loadView('admin/news/edit', compact('news', 'error'));
    }
    
    private function deleteNews() {
        $id = $_GET['id'] ?? 0;
        $news = $this->newsModel->getNewsById($id);
        
        if ($news) {
            if ($news['image']) {
                $this->deleteFile($news['image']);
            }
            $this->newsModel->deleteNews($id);
        }
        
        header('Location: /admin?action=news&message=deleted');
        exit;
    }
    
    private function deleteNewsImage() {
        $imageId = $_GET['id'] ?? 0;
        $newsId = $_GET['news_id'] ?? 0;
        
        if ($imageId && $newsId) {
            $image = $this->newsModel->getNewsImageById($imageId);
            if ($image && $image['news_id'] == $newsId) {
                if ($this->newsModel->deleteNewsImage($imageId)) {
                    $this->deleteFile($image['image_path']);
                    
                    // บันทึกกิจกรรม
                    $this->activityLogModel->logActivity([
                        'action_type' => 'delete',
                        'module' => 'news',
                        'description' => 'ลบรูปภาพข่าว',
                        'user_id' => $_SESSION['admin_id'],
                        'user_name' => $_SESSION['admin_name'],
                        'related_id' => $newsId,
                        'related_table' => 'news'
                    ]);
                    
                    header('Location: /admin?action=news&sub_action=edit&id=' . $newsId . '&message=image_deleted');
                    exit;
                }
            }
        }
        
        header('Location: /admin?action=news&error=delete_failed');
        exit;
    }
    

    
    public function manageDocuments() {
        $action = $_GET['sub_action'] ?? 'list';
        
        switch($action) {
            case 'add':
                $this->addDocument();
                break;
            case 'delete':
                $this->deleteDocument();
                break;
            default:
                $this->listDocuments();
        }
    }
    
    private function listDocuments() {
        $category = $_GET['category'] ?? 'all';
        $documents = $this->documentModel->getDocuments($category);
        $categories = $this->documentModel->getCategories();
        
        $this->loadView('admin/documents/list', compact('documents', 'categories', 'category'));
    }
    
    private function addDocument() {
        $error = null;
        
        if ($_POST && isset($_FILES['file'])) {
            $uploadResult = $this->uploadFile($_FILES['file'], 'documents');
            
            if ($uploadResult['success']) {
                $data = [
                    'title' => trim($_POST['title'] ?? ''),
                    'file_path' => $uploadResult['path'],
                    'file_type' => $uploadResult['type'],
                    'category' => trim($_POST['category'] ?? ''),
                    'group' => trim($_POST['group'] ?? '')
                ];
                
                if ($this->documentModel->createDocument($data)) {
                    header('Location: /admin?action=documents&message=added');
                    exit;
                } else {
                    $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                }
            } else {
                $error = $uploadResult['error'];
            }
        }
        
        $categories = $this->documentModel->getCategories();
        $this->loadView('admin/documents/add', compact('error', 'categories'));
    }
    
    private function deleteDocument() {
        $id = $_GET['id'] ?? 0;
        $document = $this->documentModel->getDocumentById($id);
        
        if ($document) {
            $this->deleteFile($document['file_path']);
            $this->documentModel->deleteDocument($id);
        }
        
        header('Location: /admin?action=documents&message=deleted');
        exit;
    }
    
    public function manageUsers() {
        $action = $_GET['sub_action'] ?? 'list';
        
        switch($action) {
            case 'add':
                $this->addUser();
                break;
            case 'edit':
                $this->editUser();
                break;
            case 'delete':
                $this->deleteUser();
                break;
            default:
                $this->listUsers();
        }
    }
    
    private function listUsers() {
        $users = $this->adminModel->getAllAdmins();
        $this->loadView('admin/users/list', compact('users'));
    }
    
    private function addUser() {
        $error = null;
        
        if ($_POST) {
            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => $_POST['role'] ?? 'editor',
                'group' => trim($_POST['group'] ?? '')
            ];
            
            if (empty($data['username']) || empty($data['password']) || empty($data['full_name'])) {
                $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
            } elseif ($this->adminModel->createAdmin($data)) {
                header('Location: /admin?action=users&message=added');
                exit;
            } else {
                $error = 'เกิดข้อผิดพลาดในการสร้างผู้ใช้';
            }
        }
        
        $this->loadView('admin/users/add', compact('error'));
    }
    
    private function editUser() {
        $id = $_GET['id'] ?? 0;
        $user = $this->adminModel->getAdminById($id);
        $error = null;
        
        if (!$user) {
            header('Location: /admin?action=users&error=not_found');
            exit;
        }
        
        if ($_POST) {
            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => $_POST['role'] ?? 'editor',
                'group' => trim($_POST['group'] ?? '')
            ];
            
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }
            
            if ($this->adminModel->updateAdmin($id, $data)) {
                header('Location: /admin?action=users&message=updated');
                exit;
            } else {
                $error = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
            }
        }
        
        $this->loadView('admin/users/edit', compact('user', 'error'));
    }
    
    private function deleteUser() {
        $id = $_GET['id'] ?? 0;
        
        if ($id == $_SESSION['admin_id']) {
            header('Location: /admin?action=users&error=cannot_delete_self');
            exit;
        }
        
        $this->adminModel->deleteAdmin($id);
        header('Location: /admin?action=users&message=deleted');
        exit;
    }
    
    private function uploadImage($file, $folder) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // เพิ่มเป็น 10MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'ประเภทไฟล์ไม่ถูกต้อง'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'ขนาดไฟล์ใหญ่เกินไป'];
        }
        
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/$folder/";
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = time() . '_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // ปรับปรุงคุณภาพภาพสำหรับสไลด์
            if ($folder === 'slides') {
                $this->optimizeImageForSlides($uploadPath, $file['type']);
            }
            
            return [
                'success' => true,
                'path' => "/uploads/$folder/$fileName",
                'type' => $file['type']
            ];
        }
        
        return ['success' => false, 'error' => 'ไม่สามารถอัปโหลดไฟล์ได้'];
    }
    
    /**
     * ปรับปรุงคุณภาพภาพสำหรับสไลด์
     */
    private function optimizeImageForSlides($imagePath, $imageType) {
        if (!extension_loaded('gd')) {
            return false;
        }
        
        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            return false;
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // สร้างภาพใหม่ที่มีขนาดเหมาะสมสำหรับสไลด์ (1920x1080 หรือใกล้เคียง)
        $targetWidth = 1920;
        $targetHeight = 1080;
        
        // คำนวณอัตราส่วน - ใช้ max เพื่อให้ภาพเต็มพื้นที่
        $ratio = max($targetWidth / $width, $targetHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
        
        // สร้างภาพใหม่
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // โหลดภาพต้นฉบับ
        switch ($imageType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($imagePath);
                // รักษา transparency สำหรับ PNG
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($imagePath);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($imagePath);
                break;
            default:
                return false;
        }
        
        if (!$sourceImage) {
            return false;
        }
        
        // ใช้ high-quality resampling
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // บันทึกภาพใหม่
        switch ($imageType) {
            case 'image/jpeg':
                imagejpeg($newImage, $imagePath, 100); // คุณภาพสูงสุด 100%
                break;
            case 'image/png':
                imagepng($newImage, $imagePath, 0); // ไม่บีบอัด
                break;
            case 'image/gif':
                imagegif($newImage, $imagePath);
                break;
            case 'image/webp':
                imagewebp($newImage, $imagePath, 100); // คุณภาพสูงสุด 100%
                break;
        }
        
        // ล้างหน่วยความจำ
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        
        return true;
    }
    
    private function uploadFile($file, $folder) {
        $allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        $maxSize = 10 * 1024 * 1024; // 10MB
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file['type'], $allowedTypes) || !in_array($ext, $allowedExtensions)) {
            return ['success' => false, 'error' => 'ประเภทไฟล์ไม่ถูกต้อง'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'ขนาดไฟล์ใหญ่เกินไป'];
        }
        
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/$folder/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        // เปลี่ยนชื่อไฟล์ให้ปลอดภัย
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
        $fileName = time() . '_' . $baseName . '.' . $ext;
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'path' => "/uploads/$folder/$fileName",
                'type' => $file['type']
            ];
        }
        
        return ['success' => false, 'error' => 'ไม่สามารถอัปโหลดไฟล์ได้'];
    }
    
    private function deleteFile($filePath) {
        if (empty($filePath)) {
            return false;
        }
        
        // ตรวจสอบ path ที่ถูกต้อง
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $filePath;
        
        // ตรวจสอบว่าไฟล์มีอยู่จริง
        if (file_exists($fullPath)) {
            // ตรวจสอบสิทธิ์ในการลบ
            if (is_writable($fullPath)) {
                return unlink($fullPath);
            } else {
                return false;
            }
        } else {
            return true; // ถือว่าลบสำเร็จเพราะไฟล์ไม่มีอยู่แล้ว
        }
    }
    
    public function manageCommanders() {
        $action = $_GET['sub_action'] ?? 'list';
        
        error_log("manageCommanders called with sub_action: " . $action);
        
        switch($action) {
            case 'add':
                $this->addCommander();
                break;
            case 'edit':
                $this->editCommander();
                break;
            case 'delete':
                $this->deleteCommander();
                break;
            
            default:
                $this->listCommanders();
        }
    }
    
    private function listCommanders() {
        $commanders = $this->commanderModel->getAllCommanders();
        $this->loadView('admin/commanders/list', compact('commanders'));
    }
    
    private function addCommander() {
        $error = null;
        
        if ($_POST) {
            try {
                $data = [
                    'position_name' => trim($_POST['position_name'] ?? ''),
                    'rank_id' => !empty($_POST['rank_id']) ? intval($_POST['rank_id']) : null,
                    'full_name' => trim($_POST['full_name'] ?? ''),
                    'qualifications' => trim($_POST['qualifications'] ?? ''),
                    'previous_positions' => trim($_POST['previous_positions'] ?? ''),
                    'work_phone' => trim($_POST['work_phone'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'group' => trim($_POST['group'] ?? '')
                ];
                
                // Validation
                if (empty($data['position_name'])) {
                    $error = 'กรุณากรอกตำแหน่ง';
                } elseif (strlen($data['position_name']) < 5) {
                    $error = 'ชื่อตำแหน่งต้องมีความยาวอย่างน้อย 5 ตัวอักษร';
                } elseif (empty($data['full_name'])) {
                    $error = 'กรุณากรอกชื่อ-นามสกุล';
                } elseif (strlen($data['full_name']) < 2) {
                    $error = 'ชื่อ-นามสกุลต้องมีความยาวอย่างน้อย 2 ตัวอักษร';
                } elseif (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $error = 'รูปแบบอีเมลไม่ถูกต้อง';
                } else {
                    // Handle photo upload
                    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                        $uploadResult = $this->uploadImage($_FILES['photo'], 'commanders');
                        if ($uploadResult['success']) {
                            $data['photo'] = $uploadResult['path'];
                        } else {
                            $error = $uploadResult['error'];
                        }
                    }
                    
                    if (!$error) {
                        // ตรวจสอบว่า CommanderModel มีอยู่หรือไม่
                        if (!isset($this->commanderModel)) {
                            require_once SRC_PATH . '/models/CommanderModel.php';
                            $this->commanderModel = new CommanderModel();
                        }
                        
                        if ($this->commanderModel->createCommander($data)) {
                            header('Location: /admin?action=commanders&message=added');
                            exit;
                        } else {
                            $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง';
                        }
                    }
                }
            } catch (Exception $e) {
                error_log("AddCommander error: " . $e->getMessage());
                $error = 'เกิดข้อผิดพลาดของระบบ: ' . $e->getMessage();
            }
        }
        
        // ดึงข้อมูลยศ
        $ranks = [];
        try {
            if (!isset($this->rankModel)) {
                require_once SRC_PATH . '/models/RankModel.php';
                $this->rankModel = new RankModel();
            }
            $ranks = $this->rankModel->getAllRanks();
        } catch (Exception $e) {
            error_log("Get ranks error: " . $e->getMessage());
        }
        
        $this->loadView('admin/commanders/add', compact('error', 'ranks'));
    }
    
    private function editCommander() {
        $id = $_GET['id'] ?? 0;
        $commander = $this->commanderModel->getCommanderById($id);
        $error = null;
        
        if (!$commander) {
            header('Location: /admin?action=commanders&error=not_found');
            exit;
        }
        
        if ($_POST) {
            $data = [
                'position_name' => trim($_POST['position_name'] ?? ''),
                'rank_id' => !empty($_POST['rank_id']) ? intval($_POST['rank_id']) : null,
                'full_name' => trim($_POST['full_name'] ?? ''),
                'qualifications' => trim($_POST['qualifications'] ?? ''),
                'previous_positions' => trim($_POST['previous_positions'] ?? ''),
                'work_phone' => trim($_POST['work_phone'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'group' => trim($_POST['group'] ?? '')
            ];
            
            // Validation
            if (empty($data['position_name'])) {
                $error = 'กรุณากรอกตำแหน่ง';
            } elseif (empty($data['full_name'])) {
                $error = 'กรุณากรอกชื่อ-นามสกุล';
            } else {
                // Handle photo upload
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $uploadResult = $this->uploadImage($_FILES['photo'], 'commanders');
                    if ($uploadResult['success']) {
                        // ลบรูปเก่าก่อนเซฟรูปใหม่
                        if (!empty($commander['photo'])) {
                            $this->deleteFile($commander['photo']);
                        }
                        $data['photo'] = $uploadResult['path'];
                    } else {
                        $error = $uploadResult['error'];
                    }
                }
                
                if (!$error) {
                    if ($this->commanderModel->updateCommander($id, $data)) {
                        header('Location: /admin?action=commanders&message=updated');
                        exit;
                    } else {
                        $error = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
                    }
                }
            }
        }
        
        $ranks = $this->rankModel->getAllRanks();
        $this->loadView('admin/commanders/edit', compact('commander', 'ranks', 'error'));
    }
    
    private function deleteCommander() {
        $id = $_GET['id'] ?? 0;
        $commander = $this->commanderModel->getCommanderById($id);
        
        if ($commander) {
            // ลบรูปภาพก่อนลบข้อมูล
            if (!empty($commander['photo'])) {
                $this->deleteFile($commander['photo']);
            }
            
            // ลบข้อมูลในฐานข้อมูล
            if ($this->commanderModel->deleteCommander($id)) {
                header('Location: /admin?action=commanders&message=deleted');
            } else {
                header('Location: /admin?action=commanders&error=delete_failed');
            }
        } else {
            header('Location: /admin?action=commanders&error=not_found');
        }
        exit;
    }



    public function manageRanks() {
        $action = $_GET['sub_action'] ?? 'list';
        
        switch($action) {
            case 'add':
                $this->addRank();
                break;
            case 'edit':
                $this->editRank();
                break;
            case 'delete':
                $this->deleteRank();
                break;
            default:
                $this->listRanks();
        }
    }
    
    private function listRanks() {
        $ranks = $this->rankModel->getAllRanks();
        $this->loadView('admin/ranks/list', compact('ranks'));
    }
    
    private function addRank() {
        $error = null;
        
        if ($_POST) {
            $data = [
                'rank_name_full' => trim($_POST['rank_name_full'] ?? ''),
                'rank_name_short' => trim($_POST['rank_name_short'] ?? ''),
                'rank_level' => intval($_POST['rank_level'] ?? 0),
                'group' => trim($_POST['group'] ?? '')
            ];
            
            if (empty($data['rank_name_full'])) {
                $error = 'กรุณากรอกชื่อยศเต็ม';
            } elseif (empty($data['rank_name_short'])) {
                $error = 'กรุณากรอกชื่อยศย่อ';
            } elseif ($this->rankModel->createRank($data)) {
                header('Location: /admin?action=ranks&message=added');
                exit;
            } else {
                $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
            }
        }
        
        $this->loadView('admin/ranks/add', compact('error'));
    }
    
    private function editRank() {
        $id = $_GET['id'] ?? 0;
        $rank = $this->rankModel->getRankById($id);
        $error = null;
        
        if (!$rank) {
            header('Location: /admin?action=ranks&error=not_found');
            exit;
        }
        
        if ($_POST) {
            $data = [
                'rank_name_full' => trim($_POST['rank_name_full'] ?? ''),
                'rank_name_short' => trim($_POST['rank_name_short'] ?? ''),
                'rank_level' => intval($_POST['rank_level'] ?? 0),
                'group' => trim($_POST['group'] ?? '')
            ];
            
            if ($this->rankModel->updateRank($id, $data)) {
                header('Location: /admin?action=ranks&message=updated');
                exit;
            } else {
                $error = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
            }
        }
        
        $this->loadView('admin/ranks/edit', compact('rank', 'error'));
    }
    
    private function deleteRank() {
        $id = $_GET['id'] ?? 0;
        $this->rankModel->deleteRank($id);
        header('Location: /admin?action=ranks&message=deleted');
        exit;
    }

    public function managePopup() {
        $action = $_GET['sub_action'] ?? 'list';
        switch($action) {
            case 'add':
                $this->addPopup();
                break;
            case 'edit':
                $this->editPopup();
                break;
            default:
                $this->listPopup();
        }
    }

    private function listPopup() {
        $popups = $this->popupModel->getAllPopups();
        $this->loadView('admin/popup/list', compact('popups'));
    }

    private function addPopup() {
        $error = null;
        if ($_POST) {
            $message = trim($_POST['message'] ?? '');
            $status = $_POST['status'] ?? 'inactive';
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadResult = $this->uploadImage($_FILES['image'], 'popup');
                if ($uploadResult['success']) {
                    $image = $uploadResult['path'];
                } else {
                    $error = $uploadResult['error'];
                }
            }
            if (!$error) {
                if ($status === 'active') {
                    $this->popupModel->deactivateAll();
                }
                if ($this->popupModel->createPopup($message, $image, $status)) {
                    header('Location: /admin?action=popup&message=added');
                    exit;
                } else {
                    $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                }
            }
        }
        $this->loadView('admin/popup/add', compact('error'));
    }

    private function editPopup() {
        $id = $_GET['id'] ?? 0;
        $popup = $this->popupModel->getPopupById($id);
        $error = null;
        if (!$popup) {
            header('Location: /admin?action=popup&error=not_found');
            exit;
        }
        if ($_POST) {
            $message = trim($_POST['message'] ?? '');
            $status = $_POST['status'] ?? 'inactive';
            $image = $popup['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadResult = $this->uploadImage($_FILES['image'], 'popup');
                if ($uploadResult['success']) {
                    $image = $uploadResult['path'];
                    if ($popup['image']) {
                        $this->deleteFile($popup['image']);
                    }
                } else {
                    $error = $uploadResult['error'];
                }
            }
            if (!$error) {
                if ($status === 'active') {
                    $this->popupModel->deactivateAll();
                }
                if ($this->popupModel->updatePopup($id, $message, $image, $status)) {
                    header('Location: /admin?action=popup&message=updated');
                    exit;
                } else {
                    $error = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
                }
            }
        }
        $this->loadView('admin/popup/edit', compact('popup', 'error'));
    }

    public function manageOfficerDirectory() {
        $action = $_GET['sub_action'] ?? 'list';
        switch($action) {
            case 'add':
                $this->addOfficerDirectory();
                break;
            case 'delete':
                $this->deleteOfficerDirectory();
                break;
            default:
                $this->listOfficerDirectory();
        }
    }

    private function listOfficerDirectory() {
        $files = $this->officerDirectoryModel->getAll();
        $this->loadView('admin/officer_directory/list', compact('files'));
    }

    private function addOfficerDirectory() {
        $error = null;
        $debug = null;
        if ($_POST) {
            $title = trim($_POST['title'] ?? '');
            $word_file = null;
            $pdf_file = null;
            $original_word_name = null;
            $original_pdf_name = null;
            // อัปโหลด Word
            if (isset($_FILES['word_file']) && $_FILES['word_file']['error'] === 0) {
                $original_word_name = $_FILES['word_file']['name'];
                $uploadResult = $this->uploadFile($_FILES['word_file'], 'officer_directory');
                if ($uploadResult['success']) {
                    $word_file = $uploadResult['path'];
                } else {
                    $error = $uploadResult['error'];
                    $debug = 'Word upload: ' . $uploadResult['error'];
                }
            }
            // อัปโหลด PDF
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === 0) {
                $original_pdf_name = $_FILES['pdf_file']['name'];
                $uploadResult = $this->uploadFile($_FILES['pdf_file'], 'officer_directory');
                if ($uploadResult['success']) {
                    $pdf_file = $uploadResult['path'];
                } else {
                    $error = $uploadResult['error'];
                    $debug = 'PDF upload: ' . $uploadResult['error'];
                }
            }
            if (!$word_file && !$pdf_file) {
                $error = 'กรุณาเลือกไฟล์ Word (.doc, .docx) หรือ PDF (.pdf) อย่างน้อย 1 ไฟล์';
                $debug = 'No file selected';
            }
            if (!$error) {
                $admin_id = $_SESSION['admin_id'] ?? null;
                try {
                    if ($this->officerDirectoryModel->add($title, $word_file, $pdf_file, $admin_id, $original_word_name, $original_pdf_name)) {
                        header('Location: /admin?action=officer-directory&message=added');
                        exit;
                    } else {
                        $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                        $debug = 'SQL error: ' . print_r([$title, $word_file, $pdf_file, $admin_id, $original_word_name, $original_pdf_name], true);
                    }
                } catch (\PDOException $e) {
                    $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                    $debug = 'SQL EXCEPTION: ' . $e->getMessage();
                }
            }
        }
        $this->loadView('admin/officer_directory/add', compact('error', 'debug'));
    }

    // เพิ่มฟังก์ชันแปลง Word เป็น PDF
    private function convertWordToPdf($wordPath) {
        // TODO: ใช้ไลบรารี PHPWord หรือ unoconv แปลงไฟล์ Word เป็น PDF
        // ตัวอย่าง: return '/uploads/officer_directory/xxxx.pdf';
        return null;
    }

    public function manageEnlistedDirectory() {
        $action = $_GET['sub_action'] ?? 'list';
        switch($action) {
            case 'add':
                $this->addEnlistedDirectory();
                break;
            case 'delete':
                $this->deleteEnlistedDirectory();
                break;
            default:
                $this->listEnlistedDirectory();
        }
    }

    private function listEnlistedDirectory() {
        $files = $this->officerDirectoryModel->getAllEnlisted();
        $this->loadView('admin/enlisted_directory/list', compact('files'));
    }

    private function addEnlistedDirectory() {
        $error = null;
        $original_word_name = null;
        $original_pdf_name = null;
        if ($_POST) {
            $title = trim($_POST['title'] ?? '');
            $word_file = null;
            $pdf_file = null;
            // อัปโหลด Word
            if (isset($_FILES['word_file']) && $_FILES['word_file']['error'] === 0) {
                $original_word_name = $_FILES['word_file']['name'];
                $uploadResult = $this->uploadFile($_FILES['word_file'], 'enlisted_directory');
                if ($uploadResult['success']) {
                    $word_file = $uploadResult['path'];
                } else {
                    $error = $uploadResult['error'];
                }
            }
            // อัปโหลด PDF
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === 0) {
                $original_pdf_name = $_FILES['pdf_file']['name'];
                $uploadResult = $this->uploadFile($_FILES['pdf_file'], 'enlisted_directory');
                if ($uploadResult['success']) {
                    $pdf_file = $uploadResult['path'];
                } else {
                    $error = $uploadResult['error'];
                }
            }
            if (!$word_file && !$pdf_file) {
                $error = 'กรุณาเลือกไฟล์ Word (.doc, .docx) หรือ PDF (.pdf) อย่างน้อย 1 ไฟล์';
            }
            if (!$error) {
                $admin_id = $_SESSION['admin_id'] ?? null;
                if ($this->officerDirectoryModel->addEnlisted($title, $word_file, $pdf_file, $admin_id, $original_word_name, $original_pdf_name)) {
                    header('Location: /admin?action=enlisted-directory&message=added');
                    exit;
                } else {
                    $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                }
            }
        }
        $this->loadView('admin/enlisted_directory/add', compact('error'));
    }

    private function deleteOfficerDirectory() {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if ($id) {
            $target = $this->officerDirectoryModel->getById($id);
            if ($target) {
                $publicPath = realpath(__DIR__ . '/../../public');
                if (!empty($target['word_file'])) {
                    $wordPath = $publicPath . $target['word_file'];
                    if (file_exists($wordPath)) {
                        @unlink($wordPath);
                    }
                }
                if (!empty($target['pdf_file'])) {
                    $pdfPath = $publicPath . $target['pdf_file'];
                    if (file_exists($pdfPath)) {
                        @unlink($pdfPath);
                    }
                }
                $this->officerDirectoryModel->delete($id);
            }
        }
        header('Location: /admin?action=officer-directory');
        exit;
    }

    private function deleteEnlistedDirectory() {
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if ($id) {
            $target = $this->officerDirectoryModel->getEnlistedById($id);
            if ($target) {
                $publicPath = realpath(__DIR__ . '/../../public');
                if (!empty($target['word_file'])) {
                    $wordPath = $publicPath . $target['word_file'];
                    if (file_exists($wordPath)) {
                        @unlink($wordPath);
                    }
                }
                if (!empty($target['pdf_file'])) {
                    $pdfPath = $publicPath . $target['pdf_file'];
                    if (file_exists($pdfPath)) {
                        @unlink($pdfPath);
                    }
                }
                $this->officerDirectoryModel->deleteEnlisted($id);
            }
        }
        header('Location: /admin?action=enlisted-directory');
        exit;
    }
    
    // ===== ฟังก์ชันจัดการทำเนียบ =====
    
    public function manageDirectory() {
        $action = $_GET['sub_action'] ?? 'list';
        $type = $_GET['type'] ?? 'supervisors'; // supervisors หรือ commanders
        
        switch($action) {
            case 'add':
                $this->addDirectoryItem($type);
                break;
            case 'edit':
                $this->editDirectoryItem($type);
                break;
            case 'delete':
                $this->deleteDirectoryItem($type);
                break;
            default:
                $this->listDirectoryItems($type);
        }
    }
    
    private function listDirectoryItems($type) {
        if ($type === 'supervisors') {
            $items = $this->directoryModel->getAllSupervisors();
            $title = 'ทำเนียบผู้กำกับการ';
        } else {
            $items = $this->directoryModel->getAllCommanders();
            $title = 'ทำเนียบผู้บังคับการ';
        }
        
        $this->loadView('admin/directory/list', compact('items', 'type', 'title'));
    }
    
    private function addDirectoryItem($type) {
        $error = null;
        
        if ($_POST) {
            try {
                $data = [
                    'order_number' => $_POST['order_number'] ?? '',
                    'rank' => $_POST['rank'] ?? '',
                    'first_name' => $_POST['first_name'] ?? '',
                    'last_name' => $_POST['last_name'] ?? '',
                    'start_date' => $_POST['start_date'] ?? '',
                    'end_date' => $_POST['end_date'] ?? '',
                    'is_current' => isset($_POST['is_current']) ? 1 : 0
                ];
                
                // ตรวจสอบข้อมูลที่จำเป็น
                if (empty($data['order_number']) || empty($data['rank']) || 
                    empty($data['first_name']) || empty($data['last_name'])) {
                    throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
                }
                
                // ตรวจสอบวันที่
                if (!empty($data['start_date']) && !empty($data['end_date']) && !$data['is_current']) {
                    if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
                        throw new Exception('วันที่เริ่มต้นต้องไม่เกินวันที่สิ้นสุด');
                    }
                }
                
                if ($type === 'supervisors') {
                    $result = $this->directoryModel->createSupervisor($data);
                } else {
                    $result = $this->directoryModel->createCommander($data);
                }
                
                if ($result) {
                    header('Location: /admin?action=directory&type=' . $type . '&message=added');
                    exit;
                } else {
                    throw new Exception('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // ดึงลำดับถัดไป
        if ($type === 'supervisors') {
            $nextOrder = $this->directoryModel->getNextSupervisorOrderNumber();
            $title = 'เพิ่มข้อมูลผู้กำกับการ';
        } else {
            $nextOrder = $this->directoryModel->getNextCommanderOrderNumber();
            $title = 'เพิ่มข้อมูลผู้บังคับการ';
        }
        
        $this->loadView('admin/directory/add', compact('error', 'type', 'title', 'nextOrder'));
    }
    
    private function editDirectoryItem($type) {
        $id = $_GET['id'] ?? 0;
        $error = null;
        
        if ($type === 'supervisors') {
            $item = $this->directoryModel->getSupervisorById($id);
            $title = 'แก้ไขข้อมูลผู้กำกับการ';
        } else {
            $item = $this->directoryModel->getCommanderById($id);
            $title = 'แก้ไขข้อมูลผู้บังคับการ';
        }
        
        if (!$item) {
            header('Location: /admin?action=directory&type=' . $type . '&error=not_found');
            exit;
        }
        
        if ($_POST) {
            try {
                $data = [
                    'order_number' => $_POST['order_number'] ?? '',
                    'rank' => $_POST['rank'] ?? '',
                    'first_name' => $_POST['first_name'] ?? '',
                    'last_name' => $_POST['last_name'] ?? '',
                    'start_date' => $_POST['start_date'] ?? '',
                    'end_date' => $_POST['end_date'] ?? '',
                    'is_current' => isset($_POST['is_current']) ? 1 : 0
                ];
                
                // ตรวจสอบข้อมูลที่จำเป็น
                if (empty($data['order_number']) || empty($data['rank']) || 
                    empty($data['first_name']) || empty($data['last_name'])) {
                    throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
                }
                
                // ตรวจสอบวันที่
                if (!empty($data['start_date']) && !empty($data['end_date']) && !$data['is_current']) {
                    if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
                        throw new Exception('วันที่เริ่มต้นต้องไม่เกินวันที่สิ้นสุด');
                    }
                }
                
                if ($type === 'supervisors') {
                    $result = $this->directoryModel->updateSupervisor($id, $data);
                } else {
                    $result = $this->directoryModel->updateCommander($id, $data);
                }
                
                if ($result) {
                    header('Location: /admin?action=directory&type=' . $type . '&message=updated');
                    exit;
                } else {
                    throw new Exception('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        $this->loadView('admin/directory/edit', compact('item', 'error', 'type', 'title'));
    }
    
    private function deleteDirectoryItem($type) {
        $id = $_GET['id'] ?? 0;
        
        if ($type === 'supervisors') {
            $item = $this->directoryModel->getSupervisorById($id);
        } else {
            $item = $this->directoryModel->getCommanderById($id);
        }
        
        if ($item) {
            if ($type === 'supervisors') {
                $result = $this->directoryModel->deleteSupervisor($id);
            } else {
                $result = $this->directoryModel->deleteCommander($id);
            }
            
            if ($result) {
                header('Location: /admin?action=directory&type=' . $type . '&message=deleted');
            } else {
                header('Location: /admin?action=directory&type=' . $type . '&error=delete_failed');
            }
        } else {
            header('Location: /admin?action=directory&type=' . $type . '&error=not_found');
        }
        exit;
    }

    private function loadView($view, $data = []) {
        extract($data);
        
        $viewPath = SRC_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo '<!DOCTYPE html>';
            echo '<html lang="th">';
            echo '<head>';
            echo '<meta charset="UTF-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            echo '<title>ระบบจัดการ</title>';
            echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
            echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">';
            echo '</head>';
            echo '<body>';
            echo '<div class="container mt-5">';
            echo '<div class="alert alert-warning">';
            echo '<h4><i class="fas fa-exclamation-triangle"></i> ไม่พบหน้าที่ต้องการ</h4>';
            echo '<p>ไฟล์ view: <code>' . htmlspecialchars($viewPath) . '</code> ไม่มีอยู่</p>';
            echo '<p><a href="/admin?action=dashboard" class="btn btn-primary">กลับสู่แดชบอร์ด</a></p>';
            echo '</div>';
            echo '</div>';
            echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
            echo '</body>';
            echo '</html>';
        }
    }
    
    // ==============================
    // External Links Management
    // ==============================
    


    public function manageSplash() {
        $this->loadView('admin/splash', []);
    }

    public function saveSplash() {
        require_once SRC_PATH . '/models/SplashConfigModel.php';
        $model = new SplashConfigModel();
        $config = $model->getConfig();
        $enabled = isset($_POST['enabled']) ? 1 : 0;
        $royal_duties_enabled = isset($_POST['royal_duties_enabled']) ? 1 : 0;
        $royal_duties_url = trim($_POST['royal_duties_url'] ?? '');
        
        $image_path = ($config && isset($config['image_path'])) ? $config['image_path'] : '';
        if (isset($_FILES['splash_image']) && $_FILES['splash_image']['tmp_name']) {
            $ext = strtolower(pathinfo($_FILES['splash_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed)) {
                $newName = 'splash_' . date('Ymd_His') . '.' . $ext;
                $target = '/assets/img/' . $newName;
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . $target;
                if (move_uploaded_file($_FILES['splash_image']['tmp_name'], $fullPath)) {
                    $image_path = $target;
                }
            }
        }
        $model->updateConfig($enabled, $image_path, $royal_duties_url, $royal_duties_enabled);
        
        // Clear splash session if splash is disabled to ensure immediate access
        if (!$enabled) {
            unset($_SESSION['splash_shown']);
        }
        
        // บันทึกกิจกรรม
        $this->activityLogModel->logActivity([
            'action_type' => 'update',
            'module' => 'splash',
            'description' => ActivityLogModel::createDescription('update', 'splash'),
            'user_id' => $_SESSION['admin_id'],
            'user_name' => $_SESSION['admin_name']
        ]);
        
        header('Location: /admin?action=manageSplash&saved=1');
        exit;
    }

    /**
     * จัดการสไลด์
     */
    public function manageSlides() {
        $action = $_GET['subaction'] ?? 'list';
        
        switch ($action) {
            case 'add':
                $this->addSlide();
                break;
            case 'edit':
                $this->editSlide();
                break;
            case 'delete':
                $this->deleteSlide();
                break;
            case 'status':
                $this->toggleSlideStatus();
                break;
            case 'sort':
                $this->updateSlideSort();
                break;
            default:
                $this->listSlides();
                break;
        }
    }

    /**
     * แสดงรายการสไลด์
     */
    private function listSlides() {
        $page = $_GET['page'] ?? 1;
        $perPage = 10;
        $slides = $this->slideModel->getSlidesWithPagination($page, $perPage);
        $totalSlides = $this->slideModel->getTotalSlides();
        $totalPages = ceil($totalSlides / $perPage);
        
        $title = 'จัดการสไลด์';
        $this->loadView('admin/slides/list', compact('slides', 'totalSlides', 'totalPages', 'page', 'title'));
    }

    /**
     * เพิ่มสไลด์ใหม่
     */
    private function addSlide() {
        $title = 'เพิ่มสไลด์ใหม่';
        $error = null;
        
        if ($_POST) {
            $imagePath = '';
            
            // อัปโหลดรูปภาพ
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->uploadImage($_FILES['image'], 'slides');
                if ($uploadResult && $uploadResult['success']) {
                    $imagePath = $uploadResult['path'];
                } else {
                    $error = $uploadResult['error'] ?? 'เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ';
                }
            }
            
            if (!$error && !empty($imagePath)) {
                $slideData = [
                    'title' => $_POST['title'] ?? '',
                    'subtitle' => $_POST['subtitle'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'image_path' => $imagePath,
                    'link_url' => $_POST['link_url'] ?? '',
                    'qr_code_url' => $_POST['qr_code_url'] ?? '',
                    'sort_order' => $_POST['sort_order'] ?? 0,
                    'status' => $_POST['status'] ?? 'active'
                ];
                
                $result = $this->slideModel->addSlide($slideData);
                
                if ($result) {
                    // บันทึกกิจกรรม
                    $this->activityLogModel->logActivity([
                        'action_type' => 'create',
                        'module' => 'slides',
                        'description' => 'เพิ่มสไลด์ใหม่: ' . $slideData['title'],
                        'user_id' => $_SESSION['admin_id'] ?? 0,
                        'user_name' => $_SESSION['admin_name'] ?? ''
                    ]);
                    
                    header('Location: /admin?action=slides&success=1');
                    exit;
                } else {
                    $error = 'เกิดข้อผิดพลาดในการเพิ่มสไลด์';
                }
            } else {
                if (empty($imagePath)) {
                    $error = 'กรุณาอัปโหลดรูปภาพ';
                }
            }
        }
        
        $this->loadView('admin/slides/add', compact('title', 'error'));
    }

    /**
     * แก้ไขสไลด์
     */
    private function editSlide() {
        $id = $_GET['id'] ?? 0;
        $slide = $this->slideModel->getSlideById($id);
        
        if (!$slide) {
            header('Location: /admin?action=slides&error=slide_not_found');
            exit;
        }
        
        $title = 'แก้ไขสไลด์';
        $error = null;
        
        if ($_POST) {
            $imagePath = $slide['image_path']; // ใช้รูปเดิม
            
            // อัปโหลดรูปภาพใหม่ (ถ้ามี)
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->uploadImage($_FILES['image'], 'slides');
                if ($uploadResult && $uploadResult['success']) {
                    // ลบรูปเก่า
                    $this->deleteFile($slide['image_path']);
                    $imagePath = $uploadResult['path'];
                } else {
                    $error = $uploadResult['error'] ?? 'เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ';
                }
            }
            
            $slideData = [
                'title' => $_POST['title'] ?? '',
                'subtitle' => $_POST['subtitle'] ?? '',
                'description' => $_POST['description'] ?? '',
                'image_path' => $imagePath,
                'link_url' => $_POST['link_url'] ?? '',
                'qr_code_url' => $_POST['qr_code_url'] ?? '',
                'sort_order' => $_POST['sort_order'] ?? 0,
                'status' => $_POST['status'] ?? 'active'
            ];
            
            $result = $this->slideModel->updateSlide($id, $slideData);
            
            if ($result) {
                // บันทึกกิจกรรม
                $this->activityLogModel->logActivity([
                    'action_type' => 'update',
                    'module' => 'slides',
                    'description' => 'แก้ไขสไลด์: ' . $slideData['title'],
                    'user_id' => $_SESSION['admin_id'] ?? 0,
                    'user_name' => $_SESSION['admin_name'] ?? ''
                ]);
                
                header('Location: /admin?action=slides&success=1');
                exit;
            } else {
                $error = 'เกิดข้อผิดพลาดในการอัปเดตสไลด์';
            }
        }
        
        $this->loadView('admin/slides/edit', compact('slide', 'title', 'error'));
    }

    /**
     * ลบสไลด์
     */
    private function deleteSlide() {
        $id = $_GET['id'] ?? 0;
        $slide = $this->slideModel->getSlideById($id);
        
        if ($slide) {
            $result = $this->slideModel->deleteSlide($id);
            
            if ($result) {
                // ลบรูปภาพ
                $this->deleteFile($slide['image_path']);
                
                // บันทึกกิจกรรม
                $this->activityLogModel->logActivity([
                    'action_type' => 'delete',
                    'module' => 'slides',
                    'description' => 'ลบสไลด์: ' . $slide['title'],
                    'user_id' => $_SESSION['admin_id'] ?? 0,
                    'user_name' => $_SESSION['admin_name'] ?? ''
                ]);
                
                header('Location: /admin?action=slides&success=deleted');
                exit;
            }
        }
        
        header('Location: /admin?action=slides&error=delete_failed');
        exit;
    }

    /**
     * สลับสถานะสไลด์
     */
    private function toggleSlideStatus() {
        $id = $_GET['id'] ?? 0;
        $status = $_GET['status'] ?? 'active';
        
        $result = $this->slideModel->updateStatus($id, $status);
        
        if ($result) {
            header('Location: /admin?action=slides&success=status_updated');
        } else {
            header('Location: /admin?action=slides&error=status_update_failed');
        }
        exit;
    }

    /**
     * อัปเดตลำดับสไลด์
     */
    private function updateSlideSort() {
        if ($_POST && isset($_POST['sort_order'])) {
            $sortOrders = $_POST['sort_order'];
            
            foreach ($sortOrders as $id => $order) {
                $this->slideModel->updateSortOrder($id, $order);
            }
            
            header('Location: /admin?action=slides&success=sort_updated');
            exit;
        }
        
        header('Location: /admin?action=slides&error=sort_update_failed');
        exit;
    }

    private function listAnnouncements() {
        $perPage = 10;
        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? 'all';
        $priority = $_GET['priority'] ?? 'all';
        $announcementList = $this->announcementModel->getAllAnnouncements($page, $perPage, $search, $status, $priority);
        $totalAnnouncements = $this->announcementModel->getTotalAnnouncements($search, $status, $priority);
        $totalPages = ceil($totalAnnouncements / $perPage);
        $this->loadView('admin/announcements/list', compact('announcementList', 'totalAnnouncements', 'totalPages', 'page', 'search', 'status', 'priority'));
    }
    
    private function addAnnouncement() {
        $error = null;
        
        if ($_POST) {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'status' => $_POST['status'] ?? 'draft',
                'priority' => $_POST['priority'] ?? 'normal',
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'created_by' => $_SESSION['admin_id']
            ];
            
            if (empty($data['title'])) {
                $error = 'กรุณากรอกหัวข้อประกาศ';
            } elseif (empty($data['content'])) {
                $error = 'กรุณากรอกเนื้อหาประกาศ';
            } else {
                if (!$error) {
                    if ($this->announcementModel->createAnnouncement($data)) {
                        $announcementId = $this->announcementModel->getLastInsertId();
                        if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
                            foreach ($_FILES['image']['name'] as $i => $name) {
                                if ($_FILES['image']['error'][$i] === 0) {
                                    $file = [
                                        'name' => $_FILES['image']['name'][$i],
                                        'type' => $_FILES['image']['type'][$i],
                                        'tmp_name' => $_FILES['image']['tmp_name'][$i],
                                        'error' => $_FILES['image']['error'][$i],
                                        'size' => $_FILES['image']['size'][$i]
                                    ];
                                    $uploadResult = $this->uploadImage($file, 'announcements');
                                    if ($uploadResult['success']) {
                                        $this->announcementModel->addAnnouncementImage($announcementId, $uploadResult['path']);
                                    }
                                }
                            }
                        }
                        
                        // บันทึกกิจกรรม
                        $this->activityLogModel->logActivity([
                            'action_type' => 'create',
                            'module' => 'announcements',
                            'description' => ActivityLogModel::createDescription('create', 'announcements', ['title' => $data['title']]),
                            'user_id' => $_SESSION['admin_id'],
                            'user_name' => $_SESSION['admin_name'],
                            'related_id' => $announcementId,
                            'related_table' => 'announcements'
                        ]);
                        
                        header('Location: /admin?action=announcements&message=added');
                        exit;
                    } else {
                        $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                    }
                }
            }
        }
        
        $this->loadView('admin/announcements/add', compact('error'));
    }
    
    private function editAnnouncement() {
        $id = $_GET['id'] ?? 0;
        $announcement = $this->announcementModel->getAnnouncementById($id);
        $error = null;
        
        if (!$announcement) {
            header('Location: /admin?action=announcements&error=not_found');
            exit;
        }
        
        if ($_POST) {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'status' => $_POST['status'] ?? 'draft',
                'priority' => $_POST['priority'] ?? 'normal',
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null
            ];
            
            if (empty($data['title'])) {
                $error = 'กรุณากรอกหัวข้อประกาศ';
            } elseif (empty($data['content'])) {
                $error = 'กรุณากรอกเนื้อหาประกาศ';
            } else {
                if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
                    foreach ($_FILES['image']['name'] as $i => $name) {
                        if ($_FILES['image']['error'][$i] === 0) {
                            $file = [
                                'name' => $_FILES['image']['name'][$i],
                                'type' => $_FILES['image']['type'][$i],
                                'tmp_name' => $_FILES['image']['tmp_name'][$i],
                                'error' => $_FILES['image']['error'][$i],
                                'size' => $_FILES['image']['size'][$i]
                            ];
                            $uploadResult = $this->uploadImage($file, 'announcements');
                            if ($uploadResult['success']) {
                                $this->announcementModel->addAnnouncementImage($id, $uploadResult['path']);
                            }
                        }
                    }
                }
                
                if ($this->announcementModel->updateAnnouncement($id, $data)) {
                    // บันทึกกิจกรรม
                    $this->activityLogModel->logActivity([
                        'action_type' => 'update',
                        'module' => 'announcements',
                        'description' => ActivityLogModel::createDescription('update', 'announcements', ['title' => $data['title']]),
                        'user_id' => $_SESSION['admin_id'],
                        'user_name' => $_SESSION['admin_name'],
                        'related_id' => $id,
                        'related_table' => 'announcements'
                    ]);
                    
                    header('Location: /admin?action=announcements&message=updated');
                    exit;
                } else {
                    $error = 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
                }
            }
        }
        
        $this->loadView('admin/announcements/edit', compact('announcement', 'error'));
    }
    
    private function deleteAnnouncement() {
        $id = $_GET['id'] ?? 0;
        
        if ($id) {
            $announcement = $this->announcementModel->getAnnouncementById($id);
            if ($announcement) {
                if ($this->announcementModel->deleteAnnouncement($id)) {
                    // บันทึกกิจกรรม
                    $this->activityLogModel->logActivity([
                        'action_type' => 'delete',
                        'module' => 'announcements',
                        'description' => ActivityLogModel::createDescription('delete', 'announcements', ['title' => $announcement['title']]),
                        'user_id' => $_SESSION['admin_id'],
                        'user_name' => $_SESSION['admin_name'],
                        'related_id' => $id,
                        'related_table' => 'announcements'
                    ]);
                    
                    header('Location: /admin?action=announcements&message=deleted');
                    exit;
                }
            }
        }
        
        header('Location: /admin?action=announcements&error=delete_failed');
        exit;
    }
    
    private function deleteAnnouncementImage() {
        $imageId = $_GET['id'] ?? 0;
        $announcementId = $_GET['announcement_id'] ?? 0;
        
        if ($imageId && $announcementId) {
            $image = $this->announcementModel->getAnnouncementImageById($imageId);
            if ($image && $image['announcement_id'] == $announcementId) {
                if ($this->announcementModel->deleteAnnouncementImage($imageId)) {
                    $this->deleteFile($image['image_path']);
                    
                    // บันทึกกิจกรรม
                    $this->activityLogModel->logActivity([
                        'action_type' => 'delete',
                        'module' => 'announcements',
                        'description' => 'ลบรูปภาพประกาศ',
                        'user_id' => $_SESSION['admin_id'],
                        'user_name' => $_SESSION['admin_name'],
                        'related_id' => $announcementId,
                        'related_table' => 'announcements'
                    ]);
                    
                    header('Location: /admin?action=announcements&sub_action=edit&id=' . $announcementId . '&message=image_deleted');
                    exit;
                }
            }
        }
        
        header('Location: /admin?action=announcements&error=delete_failed');
        exit;
    }
    

}
?>
