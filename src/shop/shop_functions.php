<?php
require_once 'config.php';

/**
 * دریافت دسته‌بندی‌های محصولات
 */
function get_product_categories() {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT * FROM product_categories
        ORDER BY name ASC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

/**
 * دریافت محصولات
 */
function get_products($category_id = 0, $sort = 'newest', $search = '', $page = 1, $per_page = 8) {
    global $conn;
    
    $offset = ($page - 1) * $per_page;
    
    $where_clause = "WHERE p.status = 'active'";
    $params = [];
    $types = "";
    
    if ($category_id > 0) {
        $where_clause .= " AND p.category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }
    
    if (!empty($search)) {
        $where_clause .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= "ss";
    }
    
    $order_clause = "";
    switch ($sort) {
        case 'price_low':
            $order_clause = "ORDER BY p.price ASC";
            break;
        case 'price_high':
            $order_clause = "ORDER BY p.price DESC";
            break;
        case 'popular':
            $order_clause = "ORDER BY p.sales_count DESC";
            break;
        case 'newest':
        default:
            $order_clause = "ORDER BY p.created_at DESC";
            break;
    }
    
    $query = "
        SELECT p.*, c.name as category_name
        FROM products p
        JOIN product_categories c ON p.category_id = c.id
        $where_clause
        $order_clause
        LIMIT ?, ?
    ";
    
    $params[] = $offset;
    $params[] = $per_page;
    $types .= "ii";
    
    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // دریافت تصاویر محصول
        $row['images'] = get_product_images($row['id']);
        
        // دریافت امتیاز محصول
        $row['rating'] = get_product_rating($row['id']);
        
        $products[] = $row;
    }
    
    return $products;
}

/**
 * شمارش تعداد کل محصولات
 */
function count_products($category_id = 0, $search = '') {
    global $conn;
    
    $where_clause = "WHERE p.status = 'active'";
    $params = [];
    $types = "";
    
    if ($category_id > 0) {
        $where_clause .= " AND p.category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }
    
    if (!empty($search)) {
        $where_clause .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= "ss";
    }
    
    $query = "
        SELECT COUNT(*) as count
        FROM products p
        $where_clause
    ";
    
    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'];
}

/**
 * دریافت اطلاعات یک محصول
 */
function get_product($product_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT p.*, c.name as category_name
        FROM products p
        JOIN product_categories c ON p.category_id = c.id
        WHERE p.id = ? AND p.status = 'active'
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // دریافت تصاویر محصول
        $product['images'] = get_product_images($product_id);
        
        // دریافت امتیاز محصول
        $product['rating'] = get_product_rating($product_id);
        
        return $product;
    }
    
    return false;
}

/**
 * دریافت تصاویر محصول
 */
function get_product_images($product_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT * FROM product_images
        WHERE product_id = ?
        ORDER BY is_main DESC, sort_order ASC
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    
    return $images;
}

/**
 * دریافت امتیاز محصول
 */
function get_product_rating($product_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT AVG(rating) as avg_rating, COUNT(*) as count
        FROM product_reviews
        WHERE product_id = ? AND status = 'approved'
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return [
        'avg' => round($row['avg_rating'], 1),
        'count' => $row['count']
    ];
}

/**
 * افزودن محصول به سبد خرید
 */
function add_to_cart($user_id, $product_id, $quantity = 1, $options = []) {
    global $conn;
    
    // بررسی موجودی محصول
    $product = get_product($product_id);
    if (!$product || $product['stock_quantity'] < $quantity) {
        return [
            'success' => false,
            'message' => 'موجودی محصول کافی نیست'
        ];
    }
    
    // بررسی وجود محصول در سبد خرید
    $stmt = $conn->prepare("
        SELECT id, quantity FROM cart_items
        WHERE user_id = ? AND product_id = ?
    ");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // به‌روزرسانی تعداد محصول در سبد خرید
        $cart_item = $result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $quantity;
        
        if ($product['stock_quantity'] < $new_quantity) {
            return [
                'success' => false,
                'message' => 'موجودی محصول کافی نیست'
            ];
        }
        
        $stmt = $conn->prepare("
            UPDATE cart_items
            SET quantity = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'محصول با موفقیت به سبد خرید اضافه شد'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'خطا در افزودن محصول به سبد خرید'
            ];
        }
    } else {
        // افزودن محصول جدید به سبد خرید
        $options_json = !empty($options) ? json_encode($options) : NULL;
        
        $stmt = $conn->prepare("
            INSERT INTO cart_items (
                user_id, product_id, quantity, options, created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, NOW(), NOW()
            )
        ");
        $stmt->bind_param("iiis", $user_id, $product_id, $quantity, $options_json);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'محصول با موفقیت به سبد خرید اضافه شد'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'خطا در افزودن محصول به سبد خرید'
            ];
        }
    }
}

/**
 * به‌روزرسانی تعداد محصول در سبد خرید
 */
function update_cart_item($user_id, $cart_item_id, $quantity) {
    global $conn;
    
    // بررسی وجود آیتم در سبد خرید کاربر
    $stmt = $conn->prepare("
        SELECT ci.id, ci.product_id, p.stock_quantity
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.id = ? AND ci.user_id = ?
    ");
    $stmt->bind_param("ii", $cart_item_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $cart_item = $result->fetch_assoc();
        
        // بررسی موجودی محصول
        if ($cart_item['stock_quantity'] < $quantity) {
            return [
                'success' => false,
                'message' => 'موجودی محصول کافی نیست'
            ];
        }
        
        if ($quantity <= 0) {
            // حذف محصول از سبد خرید
            $stmt = $conn->prepare("
                DELETE FROM cart_items
                WHERE id = ? AND user_id = ?
            ");
            $stmt->bind_param("ii", $cart_item_id, $user_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'محصول از سبد خرید حذف شد'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'خطا در حذف محصول از سبد خرید'
                ];
            }
        } else {
            // به‌روزرسانی تعداد محصول
            $stmt = $conn->prepare("
                UPDATE cart_items
                SET quantity = ?, updated_at = NOW()
                WHERE id = ? AND user_id = ?
            ");
            $stmt->bind_param("iii", $quantity, $cart_item_id, $user_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'سبد خرید به‌روزرسانی شد'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'خطا در به‌روزرسانی سبد خرید'
                ];
            }
        }
    } else {
        return [
            'success' => false,
            'message' => 'محصول در سبد خرید یافت نشد'
        ];
    }
}

/**
 * حذف محصول از سبد خرید
 */
function remove_from_cart($user_id, $cart_item_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        DELETE FROM cart_items
        WHERE id = ? AND user_id = ?
    ");
    $stmt->bind_param("ii", $cart_item_id, $user_id);
    
    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => 'محصول از سبد خرید حذف شد'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'خطا در حذف محصول از سبد خرید'
        ];
    }
}

/**
 * دریافت محتویات سبد خرید
 */
function get_cart_items($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT ci.*, p.name, p.price, p.discount_price, p.stock_quantity, p.sku, pi.image_path
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        LEFT JOIN (
            SELECT product_id, image_path
            FROM product_images
            WHERE is_main = 1
            LIMIT 1
        ) pi ON p.id = pi.product_id
        WHERE ci.user_id = ?
        ORDER BY ci.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cart_items = [];
    $total_price = 0;
    $total_discount = 0;
    
    while ($row = $result->fetch_assoc()) {
        $price = $row['discount_price'] > 0 ? $row['discount_price'] : $row['price'];
        $item_total = $price * $row['quantity'];
        $item_discount = ($row['price'] - $price) * $row['quantity'];
        
        $row['item_price'] = $price;
        $row['item_total'] = $item_total;
        $row['item_discount'] = $item_discount;
        
        $total_price += $item_total;
        $total_discount += $item_discount;
        
        $cart_items[] = $row;
    }
    
    return [
        'items' => $cart_items,
        'total_price' => $total_price,
        'total_discount' => $total_discount,
        'count' => count($cart_items)
    ];
}

/**
 * شمارش تعداد محصولات در سبد خرید
 */
function count_cart_items($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count
        FROM cart_items
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'];
}

/**
 * ثبت سفارش
 */
function create_order($user_id, $shipping_address, $payment_method) {
    global $conn;
    
    // دریافت محتویات سبد خرید
    $cart = get_cart_items($user_id);
    
    if (empty($cart['items'])) {
        return [
            'success' => false,
            'message' => 'سبد خرید شما خالی است'
        ];
    }
    
    // محاسبه هزینه ارسال
    $shipping_cost = calculate_shipping_cost($cart['items']);
    
    // محاسبه مبلغ کل
    $total_amount = $cart['total_price'] + $shipping_cost;
    
    // شروع تراکنش
    $conn->begin_transaction();
    
    try {
        // ایجاد سفارش
        $stmt = $conn->prepare("
            INSERT INTO orders (
                user_id, total_amount, discount_amount, shipping_cost, payment_method,
                shipping_address, status, created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW()
            )
        ");
        
        $stmt->bind_param(
            "idddss",
            $user_id, $total_amount, $cart['total_discount'], $shipping_cost,
            $payment_method, $shipping_address
        );
        
        $stmt->execute();
        $order_id = $conn->insert_id;
        
        // ثبت آیتم‌های سفارش
        foreach ($cart['items'] as $item) {
            $stmt = $conn->prepare("
                INSERT INTO order_items (
                    order_id, product_id, quantity, price, discount, options
                ) VALUES (
                    ?, ?, ?, ?, ?, ?
                )
            ");
            
            $price = $item['discount_price'] > 0 ? $item['discount_price'] : $item['price'];
            $discount = $item['price'] - $price;
            
            $stmt->bind_param(
                "iiidds",
                $order_id, $item['product_id'], $item['quantity'],
                $item['price'], $discount, $item['options']
            );
            
            $stmt->execute();
            
            // به‌روزرسانی موجودی محصول
            $stmt = $conn->prepare("
                UPDATE products
                SET stock_quantity = stock_quantity - ?, sales_count = sales_count + ?
                WHERE id = ?
            ");
            
            $stmt->bind_param("iii", $item['quantity'], $item['quantity'], $item['product_id']);
            $stmt->execute();
        }
        
        // حذف محتویات سبد خرید
        $stmt = $conn->prepare("
            DELETE FROM cart_items
            WHERE user_id = ?
        ");
        
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // ثبت تراکنش پرداخت
        $stmt = $conn->prepare("
            INSERT INTO payments (
                user_id, order_id, amount, payment_method, status, created_at
            ) VALUES (
                ?, ?, ?, ?, 'pending', NOW()
            )
        ");
        
        $stmt->bind_param("iids", $user_id, $order_id, $total_amount, $payment_method);
        $stmt->execute();
        $payment_id = $conn->insert_id;
        
        // تأیید تراکنش
        $conn->commit();
        
        return [
            'success' => true,
            'message' => 'سفارش شما با موفقیت ثبت شد',
            'order_id' => $order_id,
            'payment_id' => $payment_id
        ];
    } catch (Exception $e) {
        // برگشت تراکنش در صورت خطا
        $conn->rollback();
        
        return [
            'success' => false,
            'message' => 'خطا در ثبت سفارش: ' . $e->getMessage()
        ];
    }
}

/**
 * محاسبه هزینه ارسال
 */
function calculate_shipping_cost($cart_items) {
    // در اینجا می‌توانید منطق محاسبه هزینه ارسال را پیاده‌سازی کنید
    // برای مثال، بر اساس وزن، حجم، تعداد محصولات یا مبلغ کل سفارش
    
    $base_cost = 30000; // هزینه پایه ارسال
    
    // اگر مبلغ سفارش بیشتر از 500,000 تومان باشد، ارسال رایگان
    $total_price = 0;
    foreach ($cart_items as $item) {
        $price = $item['discount_price'] > 0 ? $item['discount_price'] : $item['price'];
        $total_price += $price * $item['quantity'];
    }
    
    if ($total_price >= 500000) {
        return 0;
    }
    
    return $base_cost;
}

/**
 * دریافت سفارش‌های کاربر
 */
function get_user_orders($user_id, $limit = 0) {
    global $conn;
    
    $limit_clause = $limit > 0 ? "LIMIT $limit" : "";
    
    $stmt = $conn->prepare("
        SELECT o.*, p.status as payment_status
        FROM orders o
        LEFT JOIN payments p ON o.id = p.order_id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
        $limit_clause
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        // دریافت آیتم‌های سفارش
        $stmt = $conn->prepare("
            SELECT oi.*, p.name, p.sku, pi.image_path
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN (
                SELECT product_id, image_path
                FROM product_images
                WHERE is_main = 1
                LIMIT 1
            ) pi ON p.id = pi.product_id
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $items_result = $stmt->get_result();
        
        $items = [];
        while ($item = $items_result->fetch_assoc()) {
            $items[] = $item;
        }
        
        $row['items'] = $items;
        $orders[] = $row;
    }
    
    return $orders;
}

/**
 * دریافت جزئیات سفارش
 */
function get_order_details($order_id, $user_id = 0) {
    global $conn;
    
    $user_clause = $user_id > 0 ? "AND o.user_id = ?" : "";
    
    $stmt = $conn->prepare("
        SELECT o.*, p.status as payment_status, p.transaction_id, p.payment_date,
               u.first_name, u.last_name, u.mobile, u.email
        FROM orders o
        LEFT JOIN payments p ON o.id = p.order_id
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ? $user_clause
    ");
    
    if ($user_id > 0) {
        $stmt->bind_param("ii", $order_id, $user_id);
    } else {
        $stmt->bind_param("i", $order_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        
        // دریافت آیتم‌های سفارش
        $stmt = $conn->prepare("
            SELECT oi.*, p.name, p.sku, pi.image_path
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN (
                SELECT product_id, image_path
                FROM product_images
                WHERE is_main = 1
                LIMIT 1
            ) pi ON p.id = pi.product_id
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items_result = $stmt->get_result();
        
        $items = [];
        while ($item = $items_result->fetch_assoc()) {
            $items[] = $item;
        }
        
        $order['items'] = $items;
        
        return $order;
    }
    
    return false;
}

/**
 * تبدیل قیمت به فرمت نمایشی
 */
function format_price($price) {
    return number_format($price) . ' تومان';
}

/**
 * تبدیل وضعیت سفارش به متن فارسی
 */
function get_order_status_text($status) {
    $statuses = [
        'pending' => 'در انتظار پرداخت',
        'processing' => 'در حال پردازش',
        'shipped' => 'ارسال شده',
        'delivered' => 'تحویل داده شده',
        'cancelled' => 'لغو شده',
        'refunded' => 'مسترد شده'
    ];
    
    return isset($statuses[$status]) ? $statuses[$status] : $status;
}

/**
 * تبدیل وضعیت پرداخت به متن فارسی
 */
function get_payment_status_text($status) {
    $statuses = [
        'pending' => 'در انتظار پرداخت',
        'completed' => 'پرداخت شده',
        'failed' => 'ناموفق',
        'refunded' => 'مسترد شده'
    ];
    
    return isset($statuses[$status]) ? $statuses[$status] : $status;
}
?>