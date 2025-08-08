SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET time_zone = '+07:00';

CREATE DATABASE IF NOT EXISTS police_training 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
utf8mb4_0900_ai_ci
USE police_training;

-- ตารางผู้ดูแลระบบ
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'editor') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตารางข่าวสาร
CREATE TABLE news (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
);
ALTER TABLE news ADD COLUMN category VARCHAR(255) DEFAULT NULL;


-- ตารางเอกสารดาวน์โหลด
CREATE TABLE documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    category VARCHAR(100),
    download_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- ตารางผู้บังคับบัญชา
CREATE TABLE commanders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    position_name VARCHAR(255) NOT NULL,
    rank_name VARCHAR(100),
    full_name VARCHAR(255) NOT NULL,
    qualifications TEXT,
    previous_positions TEXT,
    work_phone VARCHAR(50),
    email VARCHAR(255),
    photo VARCHAR(255),
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE commanders ADD COLUMN parent_id INT DEFAULT NULL AFTER sort_order;
-- ตารางยศตำรวจ
CREATE TABLE police_ranks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rank_name_full VARCHAR(100) NOT NULL,
    rank_name_short VARCHAR(50) NOT NULL,
    rank_level INT DEFAULT 0,
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

UPDATE commanders c
JOIN (
  SELECT MIN(id) as root_id, `group`
  FROM commanders
  GROUP BY `group`
) r ON c.`group` = r.`group`
SET c.parent_id = NULL
WHERE c.id = r.root_id;

UPDATE commanders c
JOIN (
  SELECT MIN(id) as root_id, `group`
  FROM commanders
  GROUP BY `group`
) r ON c.`group` = r.`group`
SET c.parent_id = r.root_id
WHERE c.id != r.root_id;
-- เพิ่ม indexes
CREATE INDEX idx_news_status ON news(status);
CREATE INDEX idx_news_created_at ON news(created_at);

CREATE INDEX idx_documents_category ON documents(category);


-- ข้อมูลเริ่มต้น
INSERT INTO admins (username, password, full_name, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ผู้ดูแลระบบ', 'admin@police.go.th', 'admin');

-- ตารางป๊อบอัพหน้าเว็บ
CREATE TABLE IF NOT EXISTS popups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    message TEXT,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ตารางไฟล์ทำเนียบกำลังสัญญาบัตร
CREATE TABLE IF NOT EXISTS officer_directory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    word_file VARCHAR(255) NULL,
    pdf_file VARCHAR(255),
    original_word_name VARCHAR(255) NULL,
    original_pdf_name VARCHAR(255) NULL,
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES admins(id) ON DELETE SET NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ตารางสำหรับเก็บไฟล์ทำเนียบกำลังพลประทวน
CREATE TABLE IF NOT EXISTS officer_directory_enlisted (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    word_file VARCHAR(255) NULL,
    pdf_file VARCHAR(255),
    original_word_name VARCHAR(255) NULL,
    original_pdf_name VARCHAR(255) NULL,
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE news_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    news_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
);

-- ตารางทำเนียบผู้กำกับการ
CREATE TABLE IF NOT EXISTS directory_supervisors (
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
);

-- ตารางทำเนียบผู้บังคับการ
CREATE TABLE IF NOT EXISTS directory_commanders (
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
);

-- เพิ่มฟิลด์ start_date และ end_date ในตาราง directory_supervisors
ALTER TABLE directory_supervisors 
ADD COLUMN start_date DATE NULL AFTER service_period,
ADD COLUMN end_date DATE NULL AFTER start_date,
ADD COLUMN is_current TINYINT(1) DEFAULT 0 AFTER end_date;

-- เพิ่มฟิลด์ start_date และ end_date ในตาราง directory_commanders
ALTER TABLE directory_commanders 
ADD COLUMN start_date DATE NULL AFTER service_period,
ADD COLUMN end_date DATE NULL AFTER start_date,
ADD COLUMN is_current TINYINT(1) DEFAULT 0 AFTER end_date;

-- สร้างตารางสำหรับจัดเก็บลิงก์ภายนอก
CREATE TABLE external_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'ชื่อปุ่ม/ลิงก์',
    url VARCHAR(500) NOT NULL COMMENT 'URL ลิงก์ภายนอก',
    description TEXT COMMENT 'คำอธิบายเพิ่มเติม',
    icon VARCHAR(100) DEFAULT 'fas fa-external-link-alt' COMMENT 'FontAwesome icon class',
    button_color VARCHAR(50) DEFAULT 'primary' COMMENT 'สีของปุ่ม (Bootstrap color)',
    target VARCHAR(20) DEFAULT '_blank' COMMENT 'target attribute (_blank, _self)',
    display_locations JSON COMMENT 'ตำแหน่งที่จะแสดงปุ่ม (homepage, news, courses, commanders, directory, footer, contact, register)',
    display_order INT DEFAULT 0 COMMENT 'ลำดับการแสดงผล',
    is_active TINYINT(1) DEFAULT 1 COMMENT 'สถานะการใช้งาน',
    created_by INT COMMENT 'ผู้สร้าง',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บลิงก์ภายนอก';

-- เพิ่มคอลัมน์ button_position สำหรับเลือกตำแหน่งการแสดงปุ่ม (ซ้าย, ขวา, บน, ล่าง)
ALTER TABLE external_links 
ADD COLUMN button_position VARCHAR(20) DEFAULT 'right' 
COMMENT 'ตำแหน่งการแสดงปุ่ม (left, right, top, bottom)' 
AFTER target;

-- อัปเดตข้อมูลที่มีอยู่ให้เป็นตำแหน่งขวา (ค่าเริ่มต้น)
UPDATE external_links SET button_position = 'right' WHERE button_position IS NULL; 

-- สร้างตารางสำหรับเก็บ Activity Log
CREATE TABLE activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action_type ENUM('create', 'update', 'delete', 'login', 'logout', 'upload', 'download', 'approve', 'reject') NOT NULL,
    module VARCHAR(50) NOT NULL COMMENT 'โมดูลที่ทำการ (news, courses, commanders, documents, etc.)',
    description TEXT NOT NULL COMMENT 'รายละเอียดกิจกรรม',
    user_id INT COMMENT 'ผู้ใช้งาน',
    user_name VARCHAR(100) COMMENT 'ชื่อผู้ใช้งาน',
    ip_address VARCHAR(45) COMMENT 'IP Address',
    user_agent TEXT COMMENT 'User Agent',
    related_id INT COMMENT 'ID ของข้อมูลที่เกี่ยวข้อง',
    related_table VARCHAR(50) COMMENT 'ตารางที่เกี่ยวข้อง',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_action_type (action_type),
    INDEX idx_module (module),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    INDEX idx_related (related_table, related_id),
    FOREIGN KEY (user_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บประวัติกิจกรรมในระบบ';

-- สำหรับการอัปเดตตารางที่มีอยู่แล้ว (ถ้ามี)
-- ALTER TABLE external_links ADD COLUMN display_locations JSON COMMENT 'ตำแหน่งที่จะแสดงปุ่ม (homepage, news, courses, commanders, directory, footer, contact, register)' AFTER target;

CREATE TABLE splash_config (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enabled TINYINT(1) NOT NULL DEFAULT 1,
    image_path VARCHAR(255) NOT NULL,
    royal_duties_url VARCHAR(500) DEFAULT '' COMMENT 'URL สำหรับปุ่มพระราชกรณียกิจ',
    royal_duties_enabled TINYINT(1) DEFAULT 1 COMMENT 'เปิด/ปิดการแสดงปุ่มพระราชกรณียกิจ',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- สร้างตาราง slides
CREATE TABLE IF NOT EXISTS slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    description TEXT,
    image_path VARCHAR(500) NOT NULL,
    link_url VARCHAR(500),
    qr_code_url VARCHAR(500),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- เพิ่ม index สำหรับการค้นหาและเรียงลำดับ
CREATE INDEX idx_slides_status ON slides(status);
CREATE INDEX idx_slides_sort_order ON slides(sort_order);
CREATE INDEX idx_slides_created_at ON slides(created_at); 

-- ตารางประกาศ
CREATE TABLE announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    status ENUM('draft', 'published') DEFAULT 'draft',
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    start_date DATE,
    end_date DATE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ตารางรูปภาพประกาศ
CREATE TABLE announcement_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    announcement_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (announcement_id) REFERENCES announcements(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- เพิ่ม index สำหรับการค้นหา
CREATE INDEX idx_announcements_status ON announcements(status);
CREATE INDEX idx_announcements_priority ON announcements(priority);
CREATE INDEX idx_announcements_dates ON announcements(start_date, end_date);
CREATE INDEX idx_announcements_created_at ON announcements(created_at);

 