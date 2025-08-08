-- อัปเดตฐานข้อมูลเพื่อเพิ่มฟิลด์ is_current (เวอร์ชันที่เข้ากันได้กับ MySQL ทุกเวอร์ชัน)
-- ใช้สำหรับฐานข้อมูลที่มีอยู่แล้ว

USE police_training;

-- เพิ่มฟิลด์ is_current ในตาราง directory_supervisors
-- ใช้วิธีตรวจสอบว่าฟิลด์มีอยู่แล้วหรือไม่
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = 'police_training' 
     AND TABLE_NAME = 'directory_supervisors' 
     AND COLUMN_NAME = 'is_current') = 0,
    'ALTER TABLE directory_supervisors ADD COLUMN is_current TINYINT(1) DEFAULT 0 AFTER end_date',
    'SELECT "Column is_current already exists in directory_supervisors" as message'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- เพิ่มฟิลด์ is_current ในตาราง directory_commanders
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = 'police_training' 
     AND TABLE_NAME = 'directory_commanders' 
     AND COLUMN_NAME = 'is_current') = 0,
    'ALTER TABLE directory_commanders ADD COLUMN is_current TINYINT(1) DEFAULT 0 AFTER end_date',
    'SELECT "Column is_current already exists in directory_commanders" as message'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- อัปเดตข้อมูลที่มีอยู่ให้เป็น 0 (ไม่ใช่ปัจจุบัน)
UPDATE directory_supervisors SET is_current = 0 WHERE is_current IS NULL;
UPDATE directory_commanders SET is_current = 0 WHERE is_current IS NULL;

-- แสดงผลการอัปเดต
SELECT 'directory_supervisors' as table_name, COUNT(*) as total_records, 
       SUM(is_current) as current_records 
FROM directory_supervisors
UNION ALL
SELECT 'directory_commanders' as table_name, COUNT(*) as total_records, 
       SUM(is_current) as current_records 
FROM directory_commanders; 