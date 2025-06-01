<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرتال کاربری - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="../indexStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<?php
    
?>
<body dir="rtl" id="lighttheme">

<!-- Mobile Sidebar Toggle -->
<div class="mobile-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</div>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="dashboard-sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>باشگاه آرین رزم</h2>
            <p>پرتال کاربری</p>
        </div>
        
        <div class="user-profile">
            <div class="user-avatar">
                <img src="" alt="تصویر کاربر">
            </div>
            <div class="user-name">علی محمدی</div>
            <div class="user-role">هنرجوی کاراته</div>
            <div class="user-status">
                <span>فعال</span>
                <div class="status-indicator"></div>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a href="#" class="menu-item active">
                <i class="fas fa-home"></i>
                <span>داشبورد</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-user"></i>
                <span>پروفایل من</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-dumbbell"></i>
                <span>کلاس‌های من</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-calendar-alt"></i>
                <span>برنامه کلاسی</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-credit-card"></i>
                <span>پرداخت‌ها</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-envelope"></i>
                <span>پیام‌ها</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>تنظیمات</span>
            </a>
        </div>
        
        <div class="sidebar-footer">
            <a href="login.html" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>خروج از حساب</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h1 class="page-title">داشبورد</h1>
            <div class="header-actions">
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <div class="notification-count">3</div>
                </div>
                <div class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">کلاس‌های فعال</div>
                    <div class="card-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                </div>
                <div class="card-value">2</div>
                <div class="card-description">شما در حال حاضر در 2 کلاس فعال شرکت می‌کنید</div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">جلسات باقیمانده</div>
                    <div class="card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="card-value">8</div>
                <div class="card-description">8 جلسه از دوره فعلی شما باقی مانده است</div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">پیام‌های جدید</div>
                    <div class="card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <div class="card-value">3</div>
                <div class="card-description">شما 3 پیام جدید از مربیان خود دارید</div>
            </div>
        </div>
        
        <!-- Upcoming Classes -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">کلاس‌های آینده</h2>
                <a href="#" class="section-action">مشاهده همه</a>
            </div>
            
            <div class="upcoming-class">
                <div class="class-time">
                    <div class="class-day">امروز</div>
                    <div class="class-hour">18:00</div>
                </div>
                <div class="class-info">
                    <div class="class-name">کاراته - سطح متوسط</div>
                    <div class="class-instructor">
                        <i class="fas fa-user"></i>
                        استاد مهدی نمازی
                    </div>
                </div>
                <a href="#" class="class-action">ورود به کلاس</a>
            </div>
            
            <div class="upcoming-class">
                <div class="class-time">
                    <div class="class-day">فردا</div>
                    <div class="class-hour">17:30</div>
                </div>
                <div class="class-info">
                    <div class="class-name">آمادگی جسمانی</div>
                    <div class="class-instructor">
                        <i class="fas fa-user"></i>
                        استاد رضا کریمی
                    </div>
                </div>
                <a href="#" class="class-action">ورود به کلاس</a>
            </div>
            
            <div class="upcoming-class">
                <div class="class-time">
                    <div class="class-day">پنج‌شنبه</div>
                    <div class="class-hour">19:00</div>
                </div>
                <div class="class-info">
                    <div class="class-name">کاراته - سطح متوسط</div>
                    <div class="class-instructor">
                        <i class="fas fa-user"></i>
                        استاد مهدی نمازی
                    </div>
                </div>
                <a href="#" class="class-action">ورود به کلاس</a>
            </div>
        </div>
        
        <!-- My Classes -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">کلاس‌های من</h2>
                <a href="#" class="section-action">مشاهده همه</a>
            </div>
            
            <table class="classes-table">
                <thead>
                    <tr>
                        <th>نام کلاس</th>
                        <th>مربی</th>
                        <th>روزهای برگزاری</th>
                        <th>ساعت</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>کاراته - سطح متوسط</td>
                        <td>استاد مهدی نمازی</td>
                        <td>یکشنبه و سه‌شنبه</td>
                        <td>18:00 - 20:00</td>
                        <td><span class="class-status status-active">فعال</span></td>
                    </tr>
                    <tr>
                        <td>آمادگی جسمانی</td>
                        <td>استاد رضا کریمی</td>
                        <td>دوشنبه و پنج‌شنبه</td>
                        <td>17:30 - 19:00</td>
                        <td><span class="class-status status-active">فعال</span></td>
                    </tr>
                    <tr>
                        <td>کونگ فو</td>
                        <td>استاد علی حسینی</td>
                        <td>شنبه و چهارشنبه</td>
                        <td>19:00 - 21:00</td>
                        <td><span class="class-status status-expired">منقضی شده</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Recent Payments -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">پرداخت‌های اخیر</h2>
                <a href="#" class="section-action">مشاهده همه</a>
            </div>
            
            <div class="payment-item">
                <div class="payment-info">
                    <div class="payment-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="payment-details">
                        <h4>شهریه کلاس کاراته</h4>
                        <p>1402/10/15</p>
                    </div>
                </div>
                <div class="payment-amount">800,000 تومان</div>
            </div>
            
            <div class="payment-item">
                <div class="payment-info">
                    <div class="payment-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="payment-details">
                        <h4>شهریه کلاس آمادگی جسمانی</h4>
                        <p>1402/09/20</p>
                    </div>
                </div>
                <div class="payment-amount">650,000 تومان</div>
            </div>
            
            <div class="payment-item">
                <div class="payment-info">
                    <div class="payment-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="payment-details">
                        <h4>خرید لباس کاراته</h4>
                        <p>1402/08/05</p>
                    </div>
                </div>
                <div class="payment-amount">450,000 تومان</div>
            </div>
        </div>
        
        <!-- Recent Messages -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">پیام‌های اخیر</h2>
                <a href="#" class="section-action">مشاهده همه</a>
            </div>
            
            <div class="message-item">
                <div class="message-avatar">
                    <img src="/placeholder.svg?height=100&width=100" alt="تصویر فرستنده">
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <div class="message-sender">استاد مهدی نمازی</div>
                        <div class="message-time">امروز، 14:30</div>
                    </div>
                    <div class="message-text">
                        سلام علی جان، یادآوری می‌کنم که فردا کلاس ساعت 18:00 برگزار می‌شود. لطفاً 15 دقیقه زودتر در باشگاه حاضر باشید تا تمرینات گرم کردن را انجام دهیم.
                    </div>
                    <div class="message-actions">
                        <a href="#" class="message-action">پاسخ</a>
                        <a href="#" class="message-action">مشاهده</a>
                    </div>
                </div>
            </div>
            
            <div class="message-item">
                <div class="message-avatar">
                    <img src="" alt="تصویر فرستنده">
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <div class="message-sender">مدیریت باشگاه</div>
                        <div class="message-time">دیروز، 10:15</div>
                    </div>
                    <div class="message-text">
                        هنرجوی گرامی، به اطلاع می‌رساند که مسابقات داخلی باشگاه در تاریخ 25 اسفند برگزار خواهد شد. در صورت تمایل به شرکت در مسابقات، لطفاً تا تاریخ 15 اسفند فرم ثبت نام را تکمیل نمایید.
                    </div>
                    <div class="message-actions">
                        <a href="#" class="message-action">پاسخ</a>
                        <a href="#" class="message-action">مشاهده</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="script.js"></script>
<script>
    // Toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
        
        // Check saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.id = 'darktheme';
            document.querySelector('.theme-toggle i').classList.remove('fa-moon');
            document.querySelector('.theme-toggle i').classList.add('fa-sun');
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('active');
            }
        });
    });
</script>
</body>
</html>