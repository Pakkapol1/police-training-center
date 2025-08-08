<?php
require_once SRC_PATH . '/models/BaseModel.php';
require_once SRC_PATH . '/models/NewsModel.php';
require_once SRC_PATH . '/models/AnnouncementModel.php';
require_once SRC_PATH . '/models/CommanderModel.php';
require_once SRC_PATH . '/models/OfficerDirectoryModel.php';
require_once SRC_PATH . '/models/DirectoryModel.php';
require_once SRC_PATH . '/models/PopupModel.php';

require_once SRC_PATH . '/models/SlideModel.php';


class HomeController extends BaseModel {
    private $newsModel;
    private $announcementModel;
    private $commanderModel;
    private $officerDirectoryModel;
    private $directoryModel;
    private $popupModel;

    private $slideModel;

    
    public function __construct() {
        parent::__construct();
        $this->newsModel = new NewsModel();
        $this->announcementModel = new AnnouncementModel();
        $this->commanderModel = new CommanderModel();
        $this->officerDirectoryModel = new OfficerDirectoryModel();
        $this->directoryModel = new DirectoryModel();
        $this->popupModel = new PopupModel();

        $this->slideModel = new SlideModel();

    }

    public function index() {
        $latestNews = $this->newsModel->getLatestNews(10); // เพิ่มจำนวนเพื่อให้เลือก 6 ข่าวที่มีรูปได้
        // ดึงรูปแรกจาก news_images มาใส่ image
        foreach ($latestNews as &$news) {
            $images = $this->newsModel->getNewsImages($news['id']);
            if (!empty($images)) {
                $news['image'] = $images[0]['image_path'];
            }
        }
        unset($news); // break reference
        
        // ดึงผู้บังคับบัญชาคนแรก (ผู้บังคับบัญชาหลัก)
        $commander = $this->commanderModel->getTopCommander();
        
        $externalLinks = [];
        $title = 'หน้าหลัก - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        $popup = $this->popupModel->getActivePopup();
        
        // ดึงข้อมูลสไลด์ที่เปิดใช้งาน
        $slides = $this->slideModel->getActiveSlides();
        
        $this->loadView('home/index', compact('title', 'latestNews', 'commander', 'externalLinks', 'popup', 'slides'));
    }

    public function news() {
        $page = $_GET['p'] ?? 1;
        $news = $this->newsModel->getPublishedNews($page, 10);
        $totalNews = $this->newsModel->getTotalNews();
        $externalLinks = [];
        $title = 'ข่าวสาร - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('news/index', compact('title', 'news', 'totalNews', 'externalLinks'));
    }

    public function newsDetail() {
        $id = $_GET['id'] ?? 0;
        $newsItem = $this->newsModel->getNewsById($id);
        
        if (!$newsItem) {
            header('HTTP/1.0 404 Not Found');
            $this->loadView('errors/404');
            return;
        }
        
        $externalLinks = [];
        $title = $newsItem['title'] . ' - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('news/detail', compact('title', 'newsItem', 'externalLinks'));
    }

    public function announcements() {
        $page = $_GET['p'] ?? 1;
        $announcements = $this->announcementModel->getPublishedAnnouncements($page, 10);
        $totalAnnouncements = $this->announcementModel->getTotalAnnouncements();
        $externalLinks = [];
        $title = 'ประกาศ - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('announcements/index', compact('title', 'announcements', 'totalAnnouncements', 'externalLinks'));
    }

    public function announcementDetail() {
        $id = $_GET['id'] ?? 0;
        $announcementItem = $this->announcementModel->getAnnouncementById($id);
        
        if (!$announcementItem) {
            header('HTTP/1.0 404 Not Found');
            $this->loadView('errors/404');
            return;
        }
        
        $externalLinks = [];
        $title = $announcementItem['title'] . ' - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('announcements/detail', compact('title', 'announcementItem', 'externalLinks'));
    }



    public function commanders() {
        $allCommanders = $this->commanderModel->getAllCommanders();
        $commanders = array_filter($allCommanders, function($commander) {
            return $commander['status'] === 'active';
        });
        
        // ⭐ สร้าง tree structure ตาม parent_id (เหมือน orgchart)
        $groupedCommanders = $this->buildCommanderTreesByGroup($commanders);
        
        // แยกกลุ่มผู้บังคับบัญชาตามหน่วยงานพร้อม hierarchy
        $commander_level1 = $groupedCommanders['ศฝร.ภ.8'] ?? [];
        $commander_admin = $groupedCommanders['ฝ่ายอำนวยการ'] ?? [];
        $commander_edu = $groupedCommanders['ฝ่ายบริการการศึกษา'] ?? [];
        $commander_training = $groupedCommanders['ฝ่ายปกครองและการฝึก'] ?? [];
        $commander_teacher = $groupedCommanders['กลุ่มงานอาจารย์'] ?? [];
        
        $externalLinks = [];
        $title = 'ผู้บังคับบัญชา - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('main/commanders', compact(
            'title', 
            'commander_level1', 
            'commander_admin', 
            'commander_edu', 
            'commander_training', 
            'commander_teacher',
            'externalLinks'
        ));
    }
    
    /**
     * สร้าง tree structure ของผู้บังคับบัญชาแยกตามกลุ่ม โดยใช้ parent_id
     */
    private function buildCommanderTreesByGroup($commanders) {
        // แยกกลุ่มก่อน
        $grouped = [];
        foreach ($commanders as $commander) {
            $group = $commander['group'] ?? 'ไม่ระบุฝ่าย';
            // ทำให้ชื่อกลุ่มเป็นมาตรฐาน
            if (in_array($group, ['ศฝร.ภ.8', 'ผู้บังคับบัญชา ศฝร.ภ.8'])) {
                $group = 'ศฝร.ภ.8';
            }
            $grouped[$group][] = $commander;
        }
        
        // สร้าง tree structure สำหรับแต่ละกลุ่ม
        $trees = [];
        foreach ($grouped as $groupName => $groupCommanders) {
            $trees[$groupName] = $this->buildCommanderTree($groupCommanders);
        }
        
        return $trees;
    }
    
    /**
     * สร้าง tree structure จาก flat array ของผู้บังคับบัญชา
     */
    private function buildCommanderTree($commanders) {
        if (empty($commanders)) {
            return [];
        }
        
        // สร้าง lookup array
        $items = [];
        foreach ($commanders as $c) {
            $parentId = null;
            if (!empty($c['parent_id']) && $c['parent_id'] != '0' && $c['parent_id'] != 0) {
                $parentId = intval($c['parent_id']);
            }
            
            $items[$c['id']] = $c;
            $items[$c['id']]['children'] = [];
            $items[$c['id']]['parent_id'] = $parentId;
        }
        
        // สร้าง tree structure
        $tree = [];
        foreach ($items as $id => &$item) {
            if ($item['parent_id'] && isset($items[$item['parent_id']])) {
                // มี parent - เพิ่มเป็น child ของ parent
                $items[$item['parent_id']]['children'][] = &$item;
            } else {
                // ไม่มี parent - เป็น root node
                $tree[] = &$item;
            }
        }
        
        // เรียงลำดับ children ตาม display_order
        $this->sortCommanderTree($tree);
        
        return $tree;
    }
    
    /**
     * เรียงลำดับ tree ตาม display_order
     */
    private function sortCommanderTree(&$tree) {
        // เรียงลำดับ current level
        usort($tree, function($a, $b) {
            $orderA = intval($a['display_order'] ?? 999);
            $orderB = intval($b['display_order'] ?? 999);
            return $orderA <=> $orderB;
        });
        
        // เรียงลำดับ children แต่ละ node
        foreach ($tree as &$node) {
            if (!empty($node['children'])) {
                $this->sortCommanderTree($node['children']);
            }
        }
    }

    public function directoryCommanders() {
        // ดึงข้อมูลผู้กำกับการและผู้บังคับการจาก DirectoryModel
        $supervisors = $this->directoryModel->getAllSupervisors();
        $commanders = $this->directoryModel->getAllCommanders();
        $title = 'ทำเนียบผู้บังคับบัญชา - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('main/directory-commanders', compact('title', 'supervisors', 'commanders'));
    }

    public function directoryOfficers() {
        $file = $this->officerDirectoryModel->getLatest();
        $title = 'ทำเนียบกำลังสัญญาบัตร - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('main/directory-officers', compact('title', 'file'));
    }

    public function directoryEnlisted() {
        $file = $this->officerDirectoryModel->getLatestEnlisted();
        $title = 'ทำเนียบกำลังพลประทวน - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('main/directory-enlisted', compact('title', 'file'));
    }



    public function contact() {
        $title = 'ติดต่อเรา - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('contact/index', compact('title'));
    }

    public function documents() {
        $title = 'เอกสารดาวน์โหลด - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('documents/index', compact('title'));
    }

    public function visionPhilosophy() {
        $title = 'วิสัยทัศน์และปรัชญา - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('main/vision-philosophy', compact('title'));
    }

    public function locationMap() {
        $title = 'แผนที่ตำแหน่งที่ตั้ง - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';

        $this->loadView('main/location-map', compact('title'));
    }

    public function history() {
        $title = 'ประวัติ - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';

        $this->loadView('main/history', compact('title'));
    }

    public function organizationStructure() {
        $title = 'โครงสร้าง ศฝร. - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';

        $this->loadView('main/org-structure', compact('title'));
    }

    public function webboard() {
        $title = 'เว็บบอร์ด - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';

        $this->loadView('main/webboard', compact('title'));
    }

    public function webmaster() {
        $title = 'ติดต่อผู้ดูแลเว็บไซต์ - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        
        $this->loadView('main/webmaster', compact('title'));
    }

    public function com() {
        // เตรียมข้อมูลผู้บังคับบัญชาแบบเดียวกับหน้า commanders แต่แสดงเฉพาะกลุ่มหลัก
        $allCommanders = $this->commanderModel->getAllCommanders();
        $commanders = array_filter($allCommanders, function($commander) {
            return $commander['status'] === 'active';
        });

        $groupedCommanders = $this->buildCommanderTreesByGroup($commanders);
        $commander_level1 = $groupedCommanders['ศฝร.ภ.8'] ?? [];

        $title = 'ผู้บังคับบัญชา - ศูนย์ฝึกอบรมตำรวจภูธรภาค 8';
        $this->loadView('main/com', compact('title', 'commander_level1'));
    }

    
    /**
     * ดึงลิงก์ภายนอกสำหรับ footer (แสดงทุกหน้า)
     */
    public function getFooterLinks() {
        return [];
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        
        $headerPath = SRC_PATH . '/views/layouts/header.php';
        $viewPath = SRC_PATH . '/views/' . $view . '.php';
        $footerPath = SRC_PATH . '/views/layouts/footer.php';
        
        if (file_exists($headerPath)) {
            include $headerPath;
        }
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "<h1>ไม่พบหน้าที่ต้องการ</h1>";
            echo "<p>ไฟล์ view: $viewPath ไม่มีอยู่</p>";
        }
        
        if (file_exists($footerPath)) {
            include $footerPath;
        }
    }
}


?>
