<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرتال کاربری - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* استایل‌های اختصاصی پرتال کاربری */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        #darktheme .dashboard-container {
            background-color: #1e1e1e;
        }

        /* Sidebar */
        .dashboard-sidebar {
            width: 280px;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: all 0.3s ease;
        }

        #darktheme .dashboard-sidebar {
            background-color: #2a2a2a;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        #darktheme .sidebar-header {
            border-bottom-color: #444;
        }

        .sidebar-header h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            color: #666;
            font-size: 14px;
        }

        #darktheme .sidebar-header p {
            color: #aaa;
        }

        .user-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        #darktheme .user-profile {
            border-bottom-color: #444;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 15px;
            border: 3px solid #e63946;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .user-role {
            color: #e63946;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .user-status {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #4CAF50;
            margin-left: 5px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: inherit;
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(230, 57, 70, 0.1);
            border-right-color: #e63946;
        }

        .menu-item i {
            margin-left: 15px;
            width: 20px;
            text-align: center;
            color: #e63946;
        }

        .sidebar-footer {
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            margin-top: auto;
        }

        #darktheme .sidebar-footer {
            border-top-color: #444;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            background-color: #e63946;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c1121f;
        }

        .logout-btn i {
            margin-left: 8px;
        }

        /* Main Content */
        .dashboard-content {
            flex: 1;
            margin-right: 280px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        #darktheme .dashboard-header {
            border-bottom-color: #444;
        }

        .page-title {
            font-size: 24px;
        }

        .header-actions {
            display: flex;
            align-items: center;
        }

        .notification-bell {
            position: relative;
            margin-left: 20px;
            cursor: pointer;
        }

        .notification-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #e63946;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .theme-toggle {
            cursor: pointer;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        #darktheme .dashboard-card {
            background-color: #2a2a2a;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(230, 57, 70, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e63946;
        }

        .card-value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-description {
            color: #666;
            font-size: 14px;
        }

        #darktheme .card-description {
            color: #aaa;
        }

        /* Sections */
        .dashboard-section {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        #darktheme .dashboard-section {
            background-color: #2a2a2a;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        #darktheme .section-header {
            border-bottom-color: #444;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
        }

        .section-action {
            color: #e63946;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .section-action:hover {
            text-decoration: underline;
        }

        /* Classes Table */
        .classes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .classes-table th, .classes-table td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }

        #darktheme .classes-table th, #darktheme .classes-table td {
            border-bottom-color: #444;
        }

        .classes-table th {
            font-weight: bold;
            color: #666;
        }

        #darktheme .classes-table th {
            color: #aaa;
        }

        .classes-table tr:last-child td {
            border-bottom: none;
        }

        .class-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background-color: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }

        .status-pending {
            background-color: rgba(255, 152, 0, 0.1);
            color: #FF9800;
        }

        .status-expired {
            background-color: rgba(244, 67, 54, 0.1);
            color: #F44336;
        }

        /* Payments */
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        #darktheme .payment-item {
            border-bottom-color: #444;
        }

        .payment-item:last-child {
            border-bottom: none;
        }

        .payment-info {
            display: flex;
            align-items: center;
        }

        .payment-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(230, 57, 70, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            color: #e63946;
        }

        .payment-details h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .payment-details p {
            color: #666;
            font-size: 14px;
        }

        #darktheme .payment-details p {
            color: #aaa;
        }

        .payment-amount {
            font-weight: bold;
            font-size: 16px;
        }

        /* Messages */
        .message-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        #darktheme .message-item {
            border-bottom-color: #444;
        }

        .message-item:last-child {
            border-bottom: none;
        }

        .message-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-left: 15px;
        }

        .message-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .message-content {
            flex: 1;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .message-sender {
            font-weight: bold;
        }

        .message-time {
            color: #666;
            font-size: 12px;
        }

        #darktheme .message-time {
            color: #aaa;
        }

        .message-text {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }

        #darktheme .message-text {
            color: #aaa;
        }

        .message-actions {
            margin-top: 10px;
        }

        .message-action {
            color: #e63946;
            text-decoration: none;
            font-size: 14px;
            margin-left: 15px;
            transition: all 0.3s ease;
        }

        .message-action:hover {
            text-decoration: underline;
        }

        /* Upcoming Classes */
        .upcoming-class {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        #darktheme .upcoming-class {
            border-bottom-color: #444;
        }

        .upcoming-class:last-child {
            border-bottom: none;
        }

        .class-time {
            width: 80px;
            text-align: center;
            margin-left: 20px;
        }

        .class-day {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .class-hour {
            color: #666;
            font-size: 14px;
        }

        #darktheme .class-hour {
            color: #aaa;
        }

        .class-info {
            flex: 1;
        }

        .class-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .class-instructor {
            color: #666;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        #darktheme .class-instructor {
            color: #aaa;
        }

        .class-instructor i {
            margin-left: 5px;
            color: #e63946;
        }

        .class-action {
            background-color: #e63946;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .class-action:hover {
            background-color: #c1121f;
        }

        /* Mobile Sidebar Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background-color: #e63946;
            color: white;
            border-radius: 5px;
            z-index: 101;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Responsive Styles */
        @media screen and (max-width: 992px) {
            .dashboard-sidebar {
                transform: translateX(100%);
                width: 250px;
            }
            
            .dashboard-sidebar.active {
                transform: translateX(0);
            }
            
            .dashboard-content {
                margin-right: 0;
            }
            
            .mobile-toggle {
                display: flex;
            }
            
            .dashboard-cards {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media screen and (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-actions {
                margin-top: 15px;
            }
            
            .classes-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
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
                <img src="/placeholder.svg?height=200&width=200" alt="تصویر کاربر">
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
                    <img src="/placeholder.svg?height=100&width=100" alt="تصویر فرستنده">
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