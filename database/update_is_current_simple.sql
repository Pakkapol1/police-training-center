-- อัปเดตฐานข้อมูลเพื่อเพิ่มฟิลด์ is_current (เวอร์ชันง่าย)
-- ใช้สำหรับฐานข้อมูลที่มีอยู่แล้ว

USE police_training;

-- เพิ่มฟิลด์ is_current ในตาราง directory_supervisors
-- ถ้าฟิลด์มีอยู่แล้วจะเกิด error แต่ไม่เป็นไร
ALTER TABLE directory_supervisors ADD COLUMN is_current TINYINT(1) DEFAULT 0 AFTER end_date;

-- เพิ่มฟิลด์ is_current ในตาราง directory_commanders
-- ถ้าฟิลด์มีอยู่แล้วจะเกิด error แต่ไม่เป็นไร
ALTER TABLE directory_commanders ADD COLUMN is_current TINYINT(1) DEFAULT 0 AFTER end_date;

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