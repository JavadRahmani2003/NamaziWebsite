<?php
require_once '../modules/config.php';
require_once 'functions.php';
require_once 'shop_functions.php';

// دریافت دسته‌بندی‌ها
$categories = get_product_categories();

// دریافت پارامترهای فیلتر
$category_id = isset($_GET['category']) ? clean_input($_GET['category']) : 0;
$sort = isset($_GET['sort']) ? clean_input($_GET['  ? clean_input($_GET['category']) : 0;
$sort = isset($_GET['sort']) ? clean_input($_GET['sort']) : 'newest';
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 8; // تعداد محصولات در هر صفحه

// دریافت محصولات
$products = get_products($category_id, $sort, $search, $page, $per_page);

// دریافت تعداد کل محصولات برای صفحه‌بندی
$total_products = count_products($category_id, $search);
$total_pages = ceil($total_products / $per_page);

// دریافت تعداد محصولات در سبد خرید
$cart_count = 0;
if (is_logged_in()) {
    $user_id = $_SESSION['user_id'];
    $cart_count = count_cart_items($user_id);
}

// بارگذاری قالب HTML
include 'shop_template.php';
?>