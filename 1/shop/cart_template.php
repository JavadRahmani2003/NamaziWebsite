<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>سبد خرید - باشگاه ورزشی آرین رزم</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* استایل‌های اختصاصی سبد خرید */
    .cart-container {
        padding: 100px 0 50px;
        background-color: #f5f5f5;
        min-height: 100vh;
    }

    #darktheme .cart-container {
        background-color: #1e1e1e;
    }

    .cart-header {
        background-color: #e63946;
        color: white;
        padding: 30px 0;
        text-align: center;
        margin-bottom: 40px;
    }

    .cart-title {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .cart-subtitle {
        font-size: 16px;
        opacity: 0.9;
    }

    .cart-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .cart-empty {
        text-align: center;
        padding: 50px 0;
    }

    .cart-empty-icon {
        font-size: 60px;
        color: #ccc;
        margin-bottom: 20px;
    }

    #darktheme .cart-empty-icon {
        color: #555;
    }

    .cart-empty-text {
        font-size: 18px;
        margin-bottom: 30px;
    }

    .cart-empty-button {
        display: inline-block;
        background-color: #e63946;
        color: white;
        padding: 12px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .cart-empty-button:hover {
        background-color: #c1121f;
    }

    .cart-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }

    .cart-items {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    #darktheme .cart-items {
        background-color: #2a2a2a;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .cart-items-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #darktheme .cart-items-header {
        border-bottom-color: #444;
    }

    .cart-items-title {
        font-size: 18px;
        font-weight: bold;
    }

    .cart-items-count {
        background-color: #e63946;
        color: white;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }

    .cart-item {
        display: flex;
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    #darktheme .cart-item {
        border-bottom-color: #444;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 100px;
        height: 100px;
        overflow: hidden;
        border-radius: 5px;
        margin-left: 20px;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .cart-item-price {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .cart-item-current-price {
        font-weight: bold;
        margin-left: 10px;
    }

    .cart-item-old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 14px;
    }

    #darktheme .cart-item-old-price {
        color: #777;
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cart-item-quantity {
        display: flex;
        align-items: center;
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        background-color: #f1faee;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #darktheme .quantity-btn {
        background-color: #333;
        border-color: #444;
        color: #f5f5f5;
    }

    .quantity-btn:hover {
        background-color: #e63946;
        color: white;
        border-color: #e63946;
    }

    .quantity-input {
        width: 50px;
        height: 30px;
        border: 1px solid #ddd;
        text-align: center;
        margin: 0 5px;
    }

    #darktheme .quantity-input {
        background-color: #333;
        border-color: #444;
        color: #f5f5f5;
    }

    .cart-item-remove {
        color: #e63946;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cart-item-remove:hover {
        color: #c1121f;
    }

    .cart-summary {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    #darktheme .cart-summary {
        background-color: #2a2a2a;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .cart-summary-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    #darktheme .cart-summary-header {
        border-bottom-color: #444;
    }

    .cart-summary-title {
        font-size: 18px;
        font-weight: bold;
    }

    .cart-summary-content {
        padding: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .summary-label {
        color: #666;
    }

    #darktheme .summary-label {
        color: #aaa;
    }

    .summary-value {
        font-weight: bold;
    }

    .summary-total {
        font-size: 18px;
        font-weight: bold;
        color: #e63946;
    }

    .cart-summary-footer {
        padding: 20px;
        border-top: 1px solid #eee;
    }

    #darktheme .cart-summary-footer {
        border-top-color: #444;
    }

    .checkout-button {
        display: block;
        width: 100%;
        background-color: #e63946;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
    }

    .checkout-button:hover {
        background-color: #c1121f;
    }

    .continue-shopping {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #666;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    #darktheme .continue-shopping {
        color: #aaa;
    }

    .continue-shopping:hover {
        color: #e63946;
    }

    /* رسپانسیو */
    @media screen and (max-width: 992px) {
        .cart-grid {
            grid-template-columns: 1fr;
        }
    }

    @media screen and (max-width: 576px) {
        .cart-item {
            flex-direction: column;
        }
        
        .cart-item-image {
            width: 100%;
            height: 200px;
            margin-left: 0;
            margin-bottom: 15px;
        }
        
        .cart-item-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>
</head>
<body dir="rtl" id="lighttheme">

<!-- منوی اصلی -->
<header class="header">
    <div class="container">
        <div class="logo">
            <a href="index.html">
                <img src="/placeholder.svg?height=60&width=150" alt="لوگوی باشگاه آرین رزم">
            </a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.html">صفحه اصلی</a></li>
                <li><a href="#">درباره ما</a></li>
                <li><a href="#">کلاس‌ها</a></li>
                <li><a href="#">مربیان</a></li>
                <li><a href="shop.php">فروشگاه</a></li>
                <li><a href="#">اخبار</a></li>
                <li><a href="#">تماس با ما</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <?php if (is_logged_in()): ?>
                <a href="dashboard.php" class="login-btn">پنل کاربری</a>
            <?php else: ?>
                <a href="login.php" class="login-btn">ورود / ثبت نام</a>
            <?php endif; ?>
            <div class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon"></i>
            </div>
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </div>
</header>

<!-- محتوای اصلی سبد خرید -->
<div class="cart-container">
    <!-- هدر سبد خرید -->
    <div class="cart-header">
        <div class="container">
            <h1 class="cart-title">سبد خرید</h1>
            <p class="cart-subtitle">محصولات انتخابی شما</p>
        </div>
    </div>

    <!-- محتوای سبد خرید -->
    <div class="cart-content">
        <?php if (empty($cart['items'])): ?>
            <!-- سبد خرید خالی -->
            <div class="cart-empty">
                <div class="cart-empty-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="cart-empty-text">سبد خرید شما خالی است</div>
                <a href="shop.php" class="cart-empty-button">رفتن به فروشگاه</a>
            </div>
        <?php else: ?>
            <!-- سبد خرید با محصول -->
            <div class="cart-grid">
                <!-- لیست محصولات -->
                <div class="cart-items">
                    <div class="cart-items-header">
                        <div class="cart-items-title">محصولات</div>
                        <div class="cart-items-count"><?php echo count($cart['items']); ?></div>
                    </div>
                    
                    <?php foreach ($cart['items'] as $item): ?>
                        <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                            <div class="cart-item-image">
                                <?php if (!empty($item['image_path'])): ?>
                                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['name']; ?>">
                                <?php else: ?>
                                    <img src="/placeholder.svg?height=100&width=100" alt="<?php echo $item['name']; ?>">
                                <?php endif; ?>
                            </div>
                            <div class="cart-item-details">
                                <h3 class="cart-item-title"><?php echo $item['name']; ?></h3>
                                <div class="cart-item-price">
                                    <div class="cart-item-current-price"><?php echo format_price($item['item_price']); ?></div>
                                    <?php if ($item['discount_price'] > 0): ?>
                                        <div class="cart-item-old-price"><?php echo format_price($item['price']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="cart-item-actions">
                                    <div class="cart-item-quantity">
                                        <div class="quantity-btn decrease-quantity">-</div>
                                        <input type="text" class="quantity-input" value="<?php echo $item['quantity']; ?>" readonly>
                                        <div class="quantity-btn increase-quantity">+</div>
                                    </div>
                                    <div class="cart-item-remove" title="حذف از سبد خرید">
                                        <i class="fas fa-trash-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- خلاصه سبد خرید -->
                <div class="cart-summary">
                    <div class="cart-summary-header">
                        <div class="cart-summary-title">خلاصه سبد خرید</div>
                    </div>
                    <div class="cart-summary-content">
                        <div class="summary-row">
                            <div class="summary-label">قیمت کل</div>
                            <div class="summary-value"><?php echo format_price($cart['total_price'] + $cart['total_discount']); ?></div>
                        </div>
                        <?php if ($cart['total_discount'] > 0): ?>
                            <div class="summary-row">
                                <div class="summary-label">تخفیف</div>
                                <div class="summary-value"><?php echo format_price($cart['total_discount']); ?></div>
                            </div>
                        <?php endif; ?>
                        <div class="summary-row">
                            <div class="summary-label">هزینه ارسال</div>
                            <?php
                                $shipping_cost = 0;
                                if ($cart['total_price'] < 500000) {
                                    $shipping_cost = 30000;
                                }
                            ?>
                            <div class="summary-value">
                                <?php if ($shipping_cost > 0): ?>
                                    <?php echo format_price($shipping_cost); ?>
                                <?php else: ?>
                                    رایگان
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">مبلغ قابل پرداخت</div>
                            <div class="summary-total"><?php echo format_price($cart['total_price'] + $shipping_cost); ?></div>
                        </div>
                    </div>
                    <div class="cart-summary-footer">
                        <a href="checkout.php" class="checkout-button">ادامه فرآیند خرید</a>
                        <a href="shop.php" class="continue-shopping">ادامه خرید</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- فوتر -->
<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="footer-column">
                <div class="footer-logo">
                    <img src="/placeholder.svg?height=60&width=150" alt="لوگوی باشگاه آرین رزم">
                </div>
                <p class="footer-description">
                    باشگاه ورزشی آرین رزم با بیش از 15 سال سابقه در زمینه آموزش هنرهای رزمی، با مربیان مجرب و امکانات مدرن آماده خدمت‌رسانی به علاقه‌مندان است.
                </p>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-telegram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">دسترسی سریع</h3>
                <ul class="footer-links">
                    <li><a href="index.html">صفحه اصلی</a></li>
                    <li><a href="#">درباره ما</a></li>
                    <li><a href="#">کلاس‌ها</a></li>
                    <li><a href="#">مربیان</a></li>
                    <li><a href="shop.php">فروشگاه</a></li>
                    <li><a href="#">اخبار</a></li>
                    <li><a href="#">تماس با ما</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">ساعات کاری</h3>
                <ul class="working-hours">
                    <li>
                        <span class="day">شنبه تا چهارشنبه:</span>
                        <span class="hours">9:00 - 21:00</span>
                    </li>
                    <li>
                        <span class="day">پنج‌شنبه:</span>
                        <span class="hours">9:00 - 19:00</span>
                    </li>
                    <li>
                        <span class="day">جمعه:</span>
                        <span class="hours">تعطیل</span>
                    </li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">تماس با ما</h3>
                <ul class="contact-info">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>تهران، خیابان ولیعصر، کوچه نیلوفر، پلاک 24</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>021-12345678</span>
                    </li>
                    <li>
                        <i class="fas fa-mobile-alt"></i>
                        <span>0912-3456789</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>info@aryanrazm.com</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="copyright">
                © تمامی حقوق برای باشگاه ورزشی آرین رزم محفوظ است. 1402
            </p>
        </div>
    </div>
</footer>

<script src="script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // تابع تغییر تم
    function toggleTheme() {
        if (document.body.id === "lighttheme") {
            document.body.id = "darktheme";
            localStorage.setItem('theme', 'dark');
            document.querySelector('.theme-toggle i').classList.remove('fa-moon');
            document.querySelector('.theme-toggle i').classList.add('fa-sun');
        } else {
            document.body.id = "lighttheme";
            localStorage.setItem('theme', 'light');
            document.querySelector('.theme-toggle i').classList.remove('fa-sun');
            document.querySelector('.theme-toggle i').classList.add('fa-moon');
        }
    }

    // بررسی تم ذخیره شده
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.id = 'darktheme';
            document.querySelector('.theme-toggle i').classList.remove('fa-moon');
            document.querySelector('.theme-toggle i').classList.add('fa-sun');
        }
        
        // منوی موبایل
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mainNav = document.querySelector('.main-nav');
        
        mobileMenuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
            this.classList.toggle('active');
        });
    });
    
    // عملیات سبد خرید
    $(document).ready(function() {
        // افزایش تعداد محصول
        $('.increase-quantity').on('click', function() {
            const cartItem = $(this).closest('.cart-item');
            const cartItemId = cartItem.data('id');
            const quantityInput = cartItem.find('.quantity-input');
            let quantity = parseInt(quantityInput.val());
            quantity++;
            
            updateCartItem(cartItemId, quantity);
        });
        
        // کاهش تعداد محصول
        $('.decrease-quantity').on('click', function() {
            const cartItem = $(this).closest('.cart-item');
            const cartItemId = cartItem.data('id');
            const quantityInput = cartItem.find('.quantity-input');
            let quantity = parseInt(quantityInput.val());
            
            if (quantity > 1) {
                quantity--;
                updateCartItem(cartItemId, quantity);
            }
        });
        
        // حذف محصول از سبد خرید
        $('.cart-item-remove').on('click', function() {
            const cartItem = $(this).closest('.cart-item');
            const cartItemId = cartItem.data('id');
            
            if (confirm('آیا از حذف این محصول از سبد خرید اطمینان دارید؟')) {
                removeFromCart(cartItemId);
            }
        });
        
        // به‌روزرسانی سبد خرید
        function updateCartItem(cartItemId, quantity) {
            $.ajax({
                url: 'shop_process.php',
                type: 'POST',
                data: {
                    action: 'update_cart',
                    cart_item_id: cartItemId,
                    quantity: quantity
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // به‌روزرسانی تعداد محصول
                        $('.cart-item[data-id="' + cartItemId + '"] .quantity-input').val(quantity);
                        
                        // به‌روزرسانی خلاصه سبد خرید
                        $('.cart-items-count').text(response.cart_count);
                        $('.summary-total').text(response.cart_total);
                        
                        // در صورت نیاز، صفحه را رفرش کنید
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('خطا در ارتباط با سرور');
                }
            });
        }
        
        // حذف محصول از سبد خرید
        function removeFromCart(cartItemId) {
            $.ajax({
                url: 'shop_process.php',
                type: 'POST',
                data: {
                    action: 'remove_from_cart',
                    cart_item_id: cartItemId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // حذف محصول از صفحه
                        $('.cart-item[data-id="' + cartItemId + '"]').fadeOut(300, function() {
                            $(this).remove();
                            
                            // به‌روزرسانی خلاصه سبد خرید
                            $('.cart-items-count').text(response.cart_count);
                            
                            // اگر سبد خرید خالی شد، صفحه را رفرش کنید
                            if (response.cart_count == 0) {
                                location.reload();
                            } else {
                                $('.summary-total').text(response.cart_total);
                            }
                        });
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('خطا در ارتباط با سرور');
                }
            });
        }
    });
</script>
</body>
</html>