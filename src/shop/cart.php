<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'shop_functions.php';

// بررسی ورود کاربر
if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = 'cart.php';
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = get_user_info($user_id);

// دریافت محتویات سبد خرید
$cart = get_cart_items($user_id);

// بارگذاری قالب HTML
include 'cart_template.php';
?>