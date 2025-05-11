<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'shop_functions.php';

// بررسی ورود کاربر
if (!is_logged_in()) {
    $response = [
        'success' => false,
        'message' => 'لطفاً ابتدا وارد حساب کاربری خود شوید'
    ];
    
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

// پردازش درخواست‌ها
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? clean_input($_POST['action']) : '';
    
    switch ($action) {
        case 'add_to_cart':
            // افزودن محصول به سبد خرید
            $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $options = isset($_POST['options']) ? $_POST['options'] : [];
            
            if ($product_id <= 0) {
                $response = [
                    'success' => false,
                    'message' => 'شناسه محصول نامعتبر است'
                ];
            } else {
                $response = add_to_cart($user_id, $product_id, $quantity, $options);
                
                // به‌روزرسانی تعداد محصولات در سبد خرید
                $response['cart_count'] = count_cart_items($user_id);
            }
            
            echo json_encode($response);
            break;
            
        case 'update_cart':
            // به‌روزرسانی سبد خرید
            $cart_item_id = isset($_POST['cart_item_id']) ? (int)$_POST['cart_item_id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
            
            if ($cart_item_id <= 0) {
                $response = [
                    'success' => false,
                    'message' => 'شناسه آیتم سبد خرید نامعتبر است'
                ];
            } else {
                $response = update_cart_item($user_id, $cart_item_id, $quantity);
                
                // به‌روزرسانی تعداد محصولات در سبد خرید
                $response['cart_count'] = count_cart_items($user_id);
                
                // دریافت اطلاعات جدید سبد خرید
                $cart = get_cart_items($user_id);
                $response['cart_total'] = format_price($cart['total_price']);
                $response['cart_discount'] = format_price($cart['total_discount']);
            }
            
            echo json_encode($response);
            break;
            
        case 'remove_from_cart':
            // حذف محصول از سبد خرید
            $cart_item_id = isset($_POST['cart_item_id']) ? (int)$_POST['cart_item_id'] : 0;
            
            if ($cart_item_id <= 0) {
                $response = [
                    'success' => false,
                    'message' => 'شناسه آیتم سبد خرید نامعتبر است'
                ];
            } else {
                $response = remove_from_cart($user_id, $cart_item_id);
                
                // به‌روزرسانی تعداد محصولات در سبد خرید
                $response['cart_count'] = count_cart_items($user_id);
                
                // دریافت اطلاعات جدید سبد خرید
                $cart = get_cart_items($user_id);
                $response['cart_total'] = format_price($cart['total_price']);
                $response['cart_discount'] = format_price($cart['total_discount']);
            }
            
            echo json_encode($response);
            break;
            
        case 'create_order':
            // ثبت سفارش
            $shipping_address = isset($_POST['shipping_address']) ? clean_input($_POST['shipping_address']) : '';
            $payment_method = isset($_POST['payment_method']) ? clean_input($_POST['payment_method']) : '';
            
            if (empty($shipping_address)) {
                $response = [
                    'success' => false,
                    'message' => 'آدرس تحویل الزامی است'
                ];
            } elseif (empty($payment_method)) {
                $response = [
                    'success' => false,
                    'message' => 'روش پرداخت الزامی است'
                ];
            } else {
                $response = create_order($user_id, $shipping_address, $payment_method);
            }
            
            echo json_encode($response);
            break;
            
        default:
            $response = [
                'success' => false,
                'message' => 'عملیات نامعتبر است'
            ];
            
            echo json_encode($response);
            break;
    }
} else {
    $response = [
        'success' => false,
        'message' => 'درخواست نامعتبر است'
    ];
    
    echo json_encode($response);
}
?>