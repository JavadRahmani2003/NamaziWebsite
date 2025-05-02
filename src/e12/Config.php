<?php
/**
 * فایل پیکربندی سیستم اخبار
 */
return [
    // تنظیمات پایگاه داده
    'db_host' => 'localhost',
    'db_username' => 'root',
    'db_password' => '',
    'db_name' => 'aryanrazm',
    'db_charset' => 'utf8mb4',
    
    // تنظیمات اخبار
    'news_per_page' => 10,
    'recent_news_count' => 5,
    'news_excerpt_length' => 200,
    
    // مسیرهای ذخیره‌سازی
    'news_images_path' => 'uploads/news/',
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif'],
    'max_image_size' => 2 * 1024 * 1024, // 2MB
    
    // تنظیمات نمایش
    'date_format' => 'Y/m/d',
    'time_format' => 'H:i',
    
    // تنظیمات امنیتی
    'enable_comments' => true,
    'moderate_comments' => true,
    'allowed_tags' => '<p><br><a><strong><em><ul><ol><li><h2><h3><h4><blockquote><img>',
];