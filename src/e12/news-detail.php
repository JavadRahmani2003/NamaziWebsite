<?php
// بارگذاری اتوماتیک کلاس‌ها
spl_autoload_register(function ($class) {
    $prefix = 'Modules\\';
    $base_dir = __DIR__ . '/modules/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// شروع جلسه
session_start();

// دریافت شناسه خبر
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pagenumber = isset($_GET['pagenumber']) ? intval($_GET['pagenumber']) : 0;

// اگر pagenumber وجود دارد، از آن استفاده کن
if ($pagenumber > 0) {
    $id = $pagenumber;
}

// ایجاد کنترلر اخبار
$newsController = new Modules\News\NewsController();

// نمایش جزئیات خبر
$content = $newsController->show($id);

// قالب صفحه
include 'header.php';
echo $content;
include 'footer.php';