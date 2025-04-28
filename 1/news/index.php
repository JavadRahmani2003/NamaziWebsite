<?php
require_once '../modules/config.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اخبار - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<script>
    function loadMenu() {
        fetch('../menu.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('mysiteMenu').innerHTML = data;
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }
    window.onload = loadMenu;
</script>
<body dir="rtl" id="lighttheme">

<!-- منوی اصلی -->
<header class="header">
    <div class="container">
        <div class="logo">
            <a href="index.html">
                <img src="/placeholder.svg?height=60&width=150" alt="لوگوی باشگاه آرین رزم">
            </a>
        </div>
        <nav id="mysitemenu"></nav>
        <div class="header-actions">
            <!-- <?php // if (is_logged_in()): ?>
                <a href="dashboard.php" class="login-btn">پنل کاربری</a>
            <?php //else: ?>
                <a href="login.php" class="login-btn">ورود / ثبت نام</a>
            <?php //endif; ?>
            <div class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon"></i>
            </div>
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div> -->
        </div>
    </div>
</header>

<!-- محتوای اصلی اخبار -->
<div class="news-container">
    <!-- هدر اخبار -->
    <div class="news-header">
        <div class="container">
            <h1 class="news-title">اخبار و رویدادها</h1>
            <p class="news-subtitle">آخرین اخبار و رویدادهای باشگاه آرین رزم</p>
        </div>
    </div>

    <!-- محتوای اخبار -->
    <div class="news-content">
        <div class="news-grid">
            <!-- اخبار اصلی -->
            <div class="news-main">
                <?php
                include('modules/GetNews.php');
                $mydb = new NewsRecieve();
                $news = $mydb->returnQueryFromDb();
                
                if ($news && $news->num_rows > 0) {
                    while ($row = $news->fetch_assoc()) {
                        $image_path = !empty($row['image_path']) ? $row['image_path'] : "/placeholder.svg?height=200&width=300";
                        $date = !empty($row['date']) ? $row['date'] : date('Y-m-d');
                        $excerpt = !empty($row['excerpt']) ? $row['excerpt'] : "خلاصه خبر در اینجا نمایش داده می‌شود...";
                        ?>
                        <div class="news-card">
                            <div class="news-card-image">
                                <img src="<?php echo $image_path; ?>" alt="<?php echo $row['header']; ?>">
                            </div>
                            <div class="news-card-content">
                                <div class="news-card-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo gregorian_to_jalali($date); ?>
                                </div>
                                <h3 class="news-card-title"><?php echo $row['header']; ?></h3>
                                <p class="news-card-excerpt"><?php echo $excerpt; ?></p>
                                <a href="news-detail.php?pagenumber=<?php echo $row['pagenumb']; ?>" class="news-card-link">ادامه مطلب</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="alert alert-info">در حال حاضر خبری برای نمایش وجود ندارد.</div>';
                }
                ?>
            </div>

            <!-- سایدبار اخبار -->
            <div class="news-sidebar">
                <div class="sidebar-header">
                    آخرین اخبار
                </div>
                <div class="sidebar-content">
                    <ul class="sidebar-news-list">
                        <?php
                        $recent_news = $mydb->returnQueryFromDb();
                        if ($recent_news && $recent_news->num_rows > 0) {
                            while ($row = $recent_news->fetch_assoc()) {
                                $date = !empty($row['date']) ? $row['date'] : date('Y-m-d');
                                ?>
                                <li class="sidebar-news-item">
                                    <a href="news-detail.php?pagenumber=<?php echo $row['pagenumb']; ?>" class="sidebar-news-link">
                                        <div class="sidebar-news-title"><?php echo $row['header']; ?></div>
                                        <div class="sidebar-news-date">
                                            <i class="far fa-calendar-alt"></i>
                                            <?php echo gregorian_to_jalali($date); ?>
                                        </div>
                                    </a>
                                </li>
                                <?php
                            }
                        } else {
                            echo '<li class="sidebar-news-item">خبری برای نمایش وجود ندارد.</li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="sidebar-header">
                    دسته‌بندی‌ها
                </div>
                <div class="sidebar-content">
                    <ul class="category-list">
                        <li class="category-item">
                            <a href="#" class="category-link">
                                <span>اخبار باشگاه</span>
                                <span class="category-count">12</span>
                            </a>
                        </li>
                        <li class="category-item">
                            <a href="#" class="category-link">
                                <span>مسابقات</span>
                                <span class="category-count">8</span>
                            </a>
                        </li>
                        <li class="category-item">
                            <a href="#" class="category-link">
                                <span>آموزش‌ها</span>
                                <span class="category-count">5</span>
                            </a>
                        </li>
                        <li class="category-item">
                            <a href="#" class="category-link">
                                <span>رویدادها</span>
                                <span class="category-count">3</span>
                            </a>
                        </li>
                        <li class="category-item">
                            <a href="#" class="category-link">
                                <span>اطلاعیه‌ها</span>
                                <span class="category-count">7</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- صفحه‌بندی -->
        <div class="news-pagination">
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a>
                </li>
            </ul>
        </div>
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
                    <li><a href="shop.php">