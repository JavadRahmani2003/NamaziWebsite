<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @charset "utf-8";
@font-face {
    font-family: 'Vazir';
    src: url("../fonts/Vazir.eot");
    src: url("../fonts/Vazir.eot?#iefix") format('embedded-opentype'),
         url("../fonts/Vazir.woff2") format('woff2'),
         url("../fonts/Vazir.woff") format('woff'),
         url("../fonts/Vazir.ttf") format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'Vazir-Bold';
    src: url("../fonts/Vazir-Bold.eot");
    src: url("../fonts/Vazir-Bold.eot?#iefix") format('embedded-opentype'),
         url("../fonts/Vazir-Bold.woff2") format('woff2'),
         url("../fonts/Vazir-Bold.woff") format('woff'),
         url("../fonts/Vazir-Bold.ttf") format('truetype');
    font-weight: bold;
    font-style: normal;
}

:root {
    --primary-color: #e63946;
    --primary-dark: #c1121f;
    --secondary-color: #1d3557;
    --light-color: #f1faee;
    --dark-color: #1d3557;
    --gray-color: #f8f9fa;
    --text-color: #333;
    --text-light: #6c757d;
    --border-color: #dee2e6;
    --shadow-color: rgba(0, 0, 0, 0.1);
}

body {
    font-family: 'Vazir', Tahoma, Arial, sans-serif;
    background-color: #f5f5f5;
    color: var(--text-color);
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

/* استایل‌های اصلی صفحه اخبار */
.MainBar {
    display: flex;
    flex-direction: row;
    width: 100%;
    max-width: 1200px;
    margin: 70px auto 30px;
    padding: 0 15px;
    box-sizing: border-box;
}

/* بخش اصلی اخبار */
.MainSeperators {
    flex: 3;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px var(--shadow-color);
    margin-left: 30px;
    overflow: hidden;
}

.Title {
    background-color: var(--primary-color);
    color: white;
    padding: 20px;
    font-family: 'Vazir-Bold', Tahoma, Arial, sans-serif;
    font-size: 24px;
    text-align: center;
    border-radius: 10px 10px 0 0;
}

.links {
    padding: 20px;
}

.linksTable {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    line-height: 1.8;
    font-size: 16px;
    text-align: justify;
}

.linksTable td {
    padding: 15px;
    border-bottom: 1px solid var(--border-color);
}

.linksTable td:last-child {
    text-align: left;
}

.linksTable a {
    display: inline-block;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease;
    padding: 8px 15px;
    border-radius: 5px;
}

.linksTable a:hover {
    background-color: var(--primary-color);
    color: white;
}

/* بخش لینک‌های سریع */
.TheFastLinks {
    flex: 1;
}

.fastLink {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px var(--shadow-color);
    padding: 20px;
    position: sticky;
    top: 90px;
}

.fastLink a {
    display: block;
    background-color: var(--primary-color) !important;
    color: white !important;
    border-radius: 5px;
    padding: 12px 15px !important;
    margin-bottom: 10px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    text-align: right;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.fastLink a:hover {
    background-color: var(--primary-dark) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* استایل‌های محتوای خبر */
.news-content {
    padding: 20px;
    line-height: 1.8;
    color: #000000;
}

.news-content h2 {
    color: var(--primary-color);
    margin-top: 30px;
    margin-bottom: 15px;
    font-size: 22px;
}

.news-content p {
    margin-bottom: 20px;
    text-align: justify;
}

.news-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
    box-shadow: 0 3px 10px var(--shadow-color);
}

.news-content blockquote {
    border-right: 4px solid var(--primary-color);
    padding: 10px 20px;
    margin: 20px 0;
    background-color: var(--gray-color);
    border-radius: 0 5px 5px 0;
    font-style: italic;
}

.news-meta {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    color: var(--text-light);
    font-size: 14px;
}

.news-meta span {
    margin-left: 15px;
    display: flex;
    align-items: center;
}

.news-meta i {
    margin-left: 5px;
}

.news-tags {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

.news-tag {
    display: inline-block;
    background-color: var(--gray-color);
    color: var(--text-color);
    padding: 5px 10px;
    border-radius: 20px;
    margin-left: 5px;
    margin-bottom: 5px;
    font-size: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.news-tag:hover {
    background-color: var(--primary-color);
    color: white;
}

/* استایل‌های رسپانسیو */
@media (max-width: 992px) {
    .MainBar {
        flex-direction: column;
    }
    
    .MainSeperators {
        margin-left: 0;
        margin-bottom: 30px;
    }
    
    .TheFastLinks {
        width: 100%;
    }
    
    .fastLink {
        position: static;
    }
    
    .fastLink a {
        display: inline-block;
        margin-left: 10px;
        margin-bottom: 10px;
    }
}

@media (max-width: 768px) {
    .Title {
        font-size: 20px;
        padding: 15px;
    }
    
    .linksTable {
        font-size: 14px;
    }
    
    .linksTable td {
        padding: 10px;
    }
}

@media (max-width: 576px) {
    .MainBar {
        padding: 0 10px;
    }
    
    .Title {
        font-size: 18px;
    }
    
    .linksTable {
        font-size: 13px;
    }
    
    .fastLink a {
        font-size: 12px;
        padding: 8px 12px !important;
    }
}

/* استایل‌های اضافی برای بهبود ظاهر */
.news-image {
    width: 100%;
    height: 300px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 20px;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.news-image:hover img {
    transform: scale(1.05);
}

.news-date {
    display: inline-block;
    background-color: var(--primary-color);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-bottom: 15px;
}

.news-author {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.news-author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    margin-left: 10px;
}

.news-author-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-author-info {
    display: flex;
    flex-direction: column;
}

.news-author-name {
    font-weight: bold;
    font-size: 14px;
}

.news-author-role {
    font-size: 12px;
    color: var(--text-light);
}

.news-share {
    display: flex;
    align-items: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

.news-share-title {
    margin-left: 15px;
    font-weight: bold;
}

.news-share-links {
    display: flex;
}

.news-share-link {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: var(--gray-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 10px;
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.3s ease;
}

.news-share-link:hover {
    background-color: var(--primary-color);
    color: white;
    transform: translateY(-3px);
}

/* استایل برای پیام‌های خالی */
.empty-message {
    padding: 30px;
    text-align: center;
    color: var(--text-light);
    font-size: 16px;
}

/* استایل برای دکمه بازگشت */
.back-button {
    display: inline-block;
    background-color: var(--secondary-color);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 20px;
    transition: all 0.3s ease;
}

.back-button:hover {
    background-color: var(--primary-color);
    transform: translateY(-2px);
}

/* استایل برای نمایش تصویر بزرگ */
.lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.lightbox img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
}

.lightbox-close {
    position: absolute;
    top: 20px;
    right: 20px;
    color: white;
    font-size: 30px;
    cursor: pointer;
}
    </style>
    <link rel="stylesheet" href="../indexStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
    function loadMenu() {
        fetch('../menuSubFolder.html')
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
        loadFooter();
    }
    function loadFooter() {
        fetch('../footerSubFolder.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('footersite').innerHTML = data;
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }
    window.onload = loadMenu;
    </script>
    <title>جزئیات خبر - باشگاه آرین رزم</title>
</head>
<body dir="rtl">
<!--Menu-->
<nav id="mysiteMenu"></nav>
<div class="MobileMenu">
    <span>Menu</span>
</div>

<div class="MainBar" style="margin-top: 70px;">
    <section class="MainSeperators">
        <div class="Title">
            <?php
            include("..\\modules\\GetNews.php");
            $result = new NewsRecieve;
            $pagenumber = $result->getNewsById($_GET['pagenumber']);
            echo $pagenumber['header'];
            ?>
        </div>
        <div class="links">
            <?php
                echo '<div class="news-content">';
                
                // تاریخ و نویسنده
                echo '<div class="news-meta">';
                echo '<span><i class="far fa-calendar-alt"></i> ' . date('Y/m/d') . '</span>';
                echo '<span><i class="far fa-user"></i> مدیر سایت</span>';
                echo '<span><i class="far fa-folder"></i> اخبار باشگاه</span>';
                echo '</div>';
                
                // // تصویر خبر (اگر وجود داشته باشد)
                // if (!empty($pagenumbers['image'])) {
                //     echo '<div class="news-image">';
                //     echo '<img src="' . $pagenumbers['image'] . '" alt="' . $pagenumbers['header'] . '">';
                //     echo '</div>';
                // }
                
                // محتوای خبر
                echo $pagenumber['thebody'];
                
                // برچسب‌ها
                echo '<div class="news-tags">';
                echo '<span>برچسب‌ها: </span>';
                echo '<a href="#" class="news-tag">آرین رزم</a>';
                echo '<a href="#" class="news-tag">باشگاه</a>';
                echo '<a href="#" class="news-tag">ورزش</a>';
                echo '</div>';
                
                // اشتراک‌گذاری
                echo '<div class="news-share">';
                echo '<span class="news-share-title">اشتراک‌گذاری:</span>';
                echo '<div class="news-share-links">';
                echo '<a href="#" class="news-share-link" data-type="facebook"><i class="fab fa-facebook-f"></i></a>';
                echo '<a href="#" class="news-share-link" data-type="twitter"><i class="fab fa-twitter"></i></a>';
                echo '<a href="#" class="news-share-link" data-type="telegram"><i class="fab fa-telegram-plane"></i></a>';
                echo '<a href="#" class="news-share-link" data-type="whatsapp"><i class="fab fa-whatsapp"></i></a>';
                echo '</div>';
                echo '</div>';
                
                // دکمه بازگشت
                echo '<a href="index.php" class="back-button"><i class="fas fa-arrow-right ml-2"></i> بازگشت به اخبار</a>';
                
                echo '</div>';
            ?>
        </div>
    </section>
    <section class="TheFastLinks">
        <div class="fastLink">
            <h3 style="color: #e63946; border-bottom: 2px solid #e63946; padding-bottom: 10px; margin-bottom: 15px;">اخبار دیگر</h3>
            <?php
            $mypagenumber = $result->returnQueryFromDb();
            while ($row = $mypagenumber->fetch_assoc()) {
                if ($row['pagenumb'] != $_GET['pagenumber']) {
                    echo "<a href='ThePage.php?pagenumber=" . $row['pagenumb'] . "'>" . $row['header'] . "</a>";
                }
            }
            ?>
        </div>
    </section>
</div>

<!-- دکمه اسکرول به بالا -->
<div class="scroll-top" onclick="scrollToTop()">
    <i class="fas fa-chevron-up"></i>
</div>

<!-- فوتر -->
<footer id="footersite"></footer>

<script src="../indexScript.js"></script>
<script>
    // اسکریپت برای صفحه اخبار

// تابع برای نمایش تصویر بزرگ
function setupImageLightbox() {
    // ایجاد عناصر لایت باکس
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    
    const lightboxImg = document.createElement('img');
    lightbox.appendChild(lightboxImg);
    
    const closeBtn = document.createElement('span');
    closeBtn.className = 'lightbox-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => {
        lightbox.style.display = 'none';
    });
    
    lightbox.appendChild(closeBtn);
    document.body.appendChild(lightbox);
    
    // اضافه کردن رویداد کلیک به تصاویر
    const newsImages = document.querySelectorAll('.news-content img');
    newsImages.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', () => {
            lightboxImg.src = img.src;
            lightbox.style.display = 'flex';
        });
    });
    
    // بستن لایت باکس با کلیک روی خود لایت باکس
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
        }
    });
}

// تابع برای اشتراک‌گذاری در شبکه‌های اجتماعی
function setupSocialSharing() {
    const shareButtons = document.querySelectorAll('.news-share-link');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            
            const type = button.getAttribute('data-type');
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            
            let shareUrl = '';
            
            switch(type) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
                case 'telegram':
                    shareUrl = `https://t.me/share/url?url=${url}&text=${title}`;
                    break;
                case 'whatsapp':
                    shareUrl = `https://api.whatsapp.com/send?text=${title} ${url}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });
}

// تابع برای اضافه کردن تاریخ و زمان به اخبار
function setupDateTimeDisplay() {
    const dateElements = document.querySelectorAll('.news-date');
    
    dateElements.forEach(element => {
        const timestamp = parseInt(element.getAttribute('data-timestamp'));
        if (!isNaN(timestamp)) {
            const date = new Date(timestamp * 1000);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            element.textContent = date.toLocaleDateString('fa-IR', options);
        }
    });
}

// اجرای توابع پس از بارگذاری صفحه
document.addEventListener('DOMContentLoaded', () => {
    setupImageLightbox();
    setupSocialSharing();
    setupDateTimeDisplay();
    
    // اضافه کردن کلاس active به لینک منوی اخبار
    const newsMenuLink = document.querySelector('nav a[href*="news"]');
    if (newsMenuLink) {
        newsMenuLink.classList.add('active');
    }
});

// تابع برای اسکرول نرم به بالای صفحه
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// نمایش دکمه اسکرول به بالا هنگام اسکرول صفحه
window.addEventListener('scroll', () => {
    const scrollTopBtn = document.querySelector('.scroll-top');
    if (scrollTopBtn) {
        if (window.pageYOffset > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    }
});
</script>
</body>
</html>