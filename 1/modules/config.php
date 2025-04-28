<?php
// تنظیمات اتصال به پایگاه داده
$db_host = 'localhost';
$db_user = 'username'; // نام کاربری پایگاه داده را وارد کنید
$db_pass = 'password'; // رمز عبور پایگاه داده را وارد کنید
$db_name = 'aryanrazm_db';

// ایجاد اتصال به پایگاه داده
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// بررسی اتصال
if ($conn->connect_error) {
    die("خطا در اتصال به پایگاه داده: " . $conn->connect_error);
}

// تنظیم کاراکترست به UTF-8
$conn->set_charset("utf8");

// شروع جلسه
session_start();

// تنظیم منطقه زمانی
date_default_timezone_set('Asia/Tehran');
?>