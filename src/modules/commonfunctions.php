<?php
require_once 'config.php';

$conn = new Database();

/**
 * تابع پاکسازی ورودی‌ها
 */
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->escapeString($data);
    return $data;
}

/**
 * تابع بررسی ورود کاربر
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * تابع هدایت کاربر به صفحه ورود در صورت عدم ورود
 */
function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * تابع هدایت کاربر به داشبورد در صورت ورود
 */
function redirect_if_logged_in() {
    if (is_logged_in()) {
        header("Location: dashboard.php");
        exit;
    }
}

/**
 * تابع دریافت اطلاعات کاربر
 */
function get_user_info($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

/**
 * تابع دریافت کلاس‌های کاربر
 */
function get_user_classes($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT cr.*, c.name, c.instructor, c.days, c.time, c.price
        FROM class_registrations cr
        JOIN classes c ON cr.class_id = c.id
        WHERE cr.user_id = ?
        ORDER BY cr.status ASC, cr.registration_date DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    
    return $classes;
}

/**
 * تابع دریافت کلاس‌های آینده کاربر
 */
function get_upcoming_classes($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT c.name, c.instructor, c.days, c.time, c.location
        FROM class_registrations cr
        JOIN classes c ON cr.class_id = c.id
        WHERE cr.user_id = ? AND cr.status = 'active'
        ORDER BY c.days ASC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    
    return $classes;
}

/**
 * تابع دریافت پرداخت‌های کاربر
 */
function get_user_payments($user_id, $limit = 5) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT * FROM payments
        WHERE user_id = ?
        ORDER BY payment_date DESC
        LIMIT ?
    ");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $payments = [];
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    
    return $payments;
}

/**
 * تابع دریافت پیام‌های کاربر
 */
function get_user_messages($user_id, $limit = 5) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT m.*, u.first_name, u.last_name, u.profile_image
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE m.receiver_id = ?
        ORDER BY m.send_date DESC
        LIMIT ?
    ");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    return $messages;
}

/**
 * تابع شمارش پیام‌های خوانده نشده
 */
function count_unread_messages($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count
        FROM messages
        WHERE receiver_id = ? AND is_read = 0
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'];
}

/**
 * تابع تبدیل تاریخ میلادی به شمسی
 */
function gregorian_to_jalali($gy, $gm, $gd) {
    $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    if ($gy > 1600) {
        $jy = 979;
        $gy -= 1600;
    } else {
        $jy = 0;
        $gy -= 621;
    }
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + (int)(($gy2 + 3) / 4) - (int)(($gy2 + 99) / 100) + (int)(($gy2 + 399) / 400) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * (int)($days / 12053);
    $days %= 12053;
    $jy += 4 * (int)($days / 1461);
    $days %= 1461;
    if ($days > 365) {
        $jy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
    $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
    return [$jy, $jm, $jd];
}

/**
 * تابع تبدیل تاریخ به فرمت شمسی
 */
function format_date($date) {
    $timestamp = strtotime($date);
    $year = date('Y', $timestamp);
    $month = date('m', $timestamp);
    $day = date('d', $timestamp);
    
    $jalali = gregorian_to_jalali($year, $month, $day);
    
    $jalali_month_names = [
        1 => 'فروردین',
        2 => 'اردیبهشت',
        3 => 'خرداد',
        4 => 'تیر',
        5 => 'مرداد',
        6 => 'شهریور',
        7 => 'مهر',
        8 => 'آبان',
        9 => 'آذر',
        10 => 'دی',
        11 => 'بهمن',
        12 => 'اسفند'
    ];
    
    return $jalali[2] . ' ' . $jalali_month_names[$jalali[1]] . ' ' . $jalali[0];
}

/**
 * تابع تبدیل عدد به فرمت پول
 */
function format_price($price) {
    return number_format($price) . ' تومان';
}
?>