<?php
/**
 * Time Helper Class
 * จัดการเวลาให้ตรงกับเขตเวลาไทย (UTC+7)
 */
class TimeHelper {
    
    /**
     * ตั้งค่า timezone เป็นเวลาไทย
     */
    public static function setThaiTimezone() {
        date_default_timezone_set('Asia/Bangkok');
    }
    
    /**
     * ได้เวลาปัจจุบันในรูปแบบ Y-m-d H:i:s
     */
    public static function now($format = 'Y-m-d H:i:s') {
        self::setThaiTimezone();
        return date($format);
    }
    
    /**
     * ได้วันที่ปัจจุบันในรูปแบบ Y-m-d
     */
    public static function today($format = 'Y-m-d') {
        self::setThaiTimezone();
        return date($format);
    }
    
    /**
     * แปลงเวลา timestamp เป็นรูปแบบที่อ่านง่าย
     */
    public static function formatDateTime($datetime, $format = 'd/m/Y H:i') {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return '-';
        }
        
        self::setThaiTimezone();
        return date($format, strtotime($datetime));
    }
    
    /**
     * แปลงเวลา timestamp เป็นรูปแบบวันที่ไทย
     */
    public static function formatThaiDate($datetime, $showTime = false) {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return '-';
        }
        
        self::setThaiTimezone();
        $thaiMonths = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม',
            4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
            7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน',
            10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        
        $timestamp = strtotime($datetime);
        $day = date('j', $timestamp);
        $month = $thaiMonths[(int)date('n', $timestamp)];
        $year = date('Y', $timestamp) + 543; // เปลี่ยนเป็นปี พ.ศ.
        
        $result = "$day $month $year";
        
        if ($showTime) {
            $time = date('H:i', $timestamp);
            $result .= " เวลา $time น.";
        }
        
        return $result;
    }
    
    /**
     * คำนวณความต่างของเวลา
     */
    public static function timeAgo($datetime) {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return '-';
        }
        
        self::setThaiTimezone();
        $time = time() - strtotime($datetime);
        
        if ($time < 60) {
            return 'เมื่อสักครู่';
        } elseif ($time < 3600) {
            $minutes = floor($time / 60);
            return "$minutes นาทีที่แล้ว";
        } elseif ($time < 86400) {
            $hours = floor($time / 3600);
            return "$hours ชั่วโมงที่แล้ว";
        } elseif ($time < 2592000) {
            $days = floor($time / 86400);
            return "$days วันที่แล้ว";
        } else {
            return self::formatThaiDate($datetime);
        }
    }
    
    /**
     * ตรวจสอบว่าเวลาที่ส่งมาถูกต้องหรือไม่
     */
    public static function isValidDateTime($datetime) {
        return !empty($datetime) && $datetime !== '0000-00-00 00:00:00' && strtotime($datetime) !== false;
    }
    
    /**
     * แปลง timestamp จาก UTC เป็นเวลาไทย
     */
    public static function convertFromUTC($utcDatetime) {
        if (!self::isValidDateTime($utcDatetime)) {
            return null;
        }
        
        $utc = new DateTime($utcDatetime, new DateTimeZone('UTC'));
        $bangkok = new DateTimeZone('Asia/Bangkok');
        $utc->setTimezone($bangkok);
        
        return $utc->format('Y-m-d H:i:s');
    }
    
    /**
     * แปลง timestamp จากเวลาไทยเป็น UTC
     */
    public static function convertToUTC($bangkokDatetime) {
        if (!self::isValidDateTime($bangkokDatetime)) {
            return null;
        }
        
        $bangkok = new DateTime($bangkokDatetime, new DateTimeZone('Asia/Bangkok'));
        $utc = new DateTimeZone('UTC');
        $bangkok->setTimezone($utc);
        
        return $bangkok->format('Y-m-d H:i:s');
    }
}
