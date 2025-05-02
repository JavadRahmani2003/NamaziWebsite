<?php
namespace Modules\News\Traits;

trait DateFormatter {
    /**
     * تبدیل تاریخ میلادی به شمسی
     * 
     * @param string $date تاریخ میلادی (Y-m-d)
     * @return string تاریخ شمسی
     */
    public function gregorianToJalali($date) {
        if (empty($date)) {
            return '';
        }
        
        // تبدیل تاریخ به timestamp
        $timestamp = strtotime($date);
        
        // استفاده از تابع jdate اگر موجود باشد
        if (function_exists('jdate')) {
            return jdate('Y/m/d', $timestamp);
        }
        
        // پیاده‌سازی دستی تبدیل تاریخ
        list($year, $month, $day) = explode('-', date('Y-m-d', $timestamp));
        
        $gDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $jDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
        
        $gy = $year - 1600;
        $gm = $month - 1;
        $gd = $day - 1;
        
        $gDayNo = 365 * $gy + intval(($gy + 3) / 4) - intval(($gy + 99) / 100) + intval(($gy + 399) / 400);
        
        for ($i = 0; $i < $gm; ++$i) {
            $gDayNo += $gDaysInMonth[$i];
        }
        
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0))) {
            $gDayNo++;
        }
        
        $gDayNo += $gd;
        
        $jDayNo = $gDayNo - 79;
        
        $jNp = intval($jDayNo / 12053);
        $jDayNo %= 12053;
        
        $jy = 979 + 33 * $jNp + 4 * intval($jDayNo / 1461);
        
        $jDayNo %= 1461;
        
        if ($jDayNo >= 366) {
            $jy += intval(($jDayNo - 1) / 365);
            $jDayNo = ($jDayNo - 1) % 365;
        }
        
        for ($i = 0; $i < 11 && $jDayNo >= $jDaysInMonth[$i]; ++$i) {
            $jDayNo -= $jDaysInMonth[$i];
        }
        
        $jm = $i + 1;
        $jd = $jDayNo + 1;
        
        return $jy . '/' . (($jm < 10) ? '0' . $jm : $jm) . '/' . (($jd < 10) ? '0' . $jd : $jd);
    }
    
    /**
     * تبدیل تاریخ شمسی به میلادی
     * 
     * @param string $jalaliDate تاریخ شمسی (Y/m/d)
     * @return string تاریخ میلادی
     */
    public function jalaliToGregorian($jalaliDate) {
        if (empty($jalaliDate)) {
            return '';
        }
        
        // جداسازی اجزای تاریخ شمسی
        list($jy, $jm, $jd) = explode('/', $jalaliDate);
        
        $gDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $jDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
        
        $jy += 1595;
        $days = -355668 + (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)(((($jy % 33) + 3) / 4)));
        
        for ($i = 0; $i < ($jm - 1); ++$i) {
            $days += $jDaysInMonth[$i];
        }
        
        $days += $jd;
        $gYear = 400 * ((int)($days / 146097));
        $days %= 146097;
        
        if ($days > 36524) {
            $gYear += 100 * ((int)(--$days / 36524));
            $days %= 36524;
            
            if ($days >= 365) {
                $days++;
            }
        }
        
        $gYear += 4 * ((int)($days / 1461));
        $days %= 1461;
        
        if ($days > 365) {
            $gYear += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        
        $gd = $days + 1;
        
        $sal_a = [0, 31, ((($gYear % 4 == 0) && ($gYear % 100 != 0)) || ($gYear % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        
        for ($gm = 0; $gm < 13 && $gd > $sal_a[$gm]; ++$gm) {
            $gd -= $sal_a[$gm];
        }
        
        return ($gYear) . '-' . (($gm < 10) ? '0' . $gm : $gm) . '-' . (($gd < 10) ? '0' . $gd : $gd);
    }
    
    /**
     * فرمت‌دهی تاریخ و زمان
     * 
     * @param string $date تاریخ و زمان
     * @param string $format فرمت مورد نظر
     * @param bool $convertToJalali آیا به شمسی تبدیل شود؟
     * @return string تاریخ و زمان فرمت‌دهی شده
     */
    public function formatDate($date, $format = null, $convertToJalali = true) {
        if (empty($date)) {
            return '';
        }
        
        $config = require_once __DIR__ . '/../Config.php';
        $format = $format ?? $config['date_format'];
        
        $timestamp = strtotime($date);
        
        if ($convertToJalali) {
            return $this->gregorianToJalali(date('Y-m-d', $timestamp));
        }
        
        return date($format, $timestamp);
    }
    
    /**
     * محاسبه زمان سپری شده از یک تاریخ
     * 
     * @param string $date تاریخ
     * @return string زمان سپری شده به صورت متنی
     */
    public function timeAgo($date) {
        if (empty($date)) {
            return '';
        }
        
        $timestamp = strtotime($date);
        $current = time();
        $diff = $current - $timestamp;
        
        if ($diff < 60) {
            return 'چند لحظه پیش';
        } elseif ($diff < 3600) {
            $minutes = round($diff / 60);
            return $minutes . ' دقیقه پیش';
        } elseif ($diff < 86400) {
            $hours = round($diff / 3600);
            return $hours . ' ساعت پیش';
        } elseif ($diff < 604800) {
            $days = round($diff / 86400);
            return $days . ' روز پیش';
        } elseif ($diff < 2592000) {
            $weeks = round($diff / 604800);
            return $weeks . ' هفته پیش';
        } elseif ($diff < 31536000) {
            $months = round($diff / 2592000);
            return $months . ' ماه پیش';
        } else {
            $years = round($diff / 31536000);
            return $years . ' سال پیش';
        }
    }
}