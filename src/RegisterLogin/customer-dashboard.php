<?php
session_start();
require_once '../modules/database.php';

// بررسی ورود کاربر
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$customer_id = $_SESSION['customer_id'];

// دریافت اطلاعات کاربر
$user_query = "SELECT * FROM registrations WHERE id = $customer_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

// دریافت کلاس‌های ثبت نام شده
$classes_query = "
    SELECT c.*, rc.start_date, rc.end_date, rc.is_active as enrollment_active
    FROM registration_classes rc
    JOIN classes c ON rc.class_id = c.id
    WHERE rc.registration_id = $customer_id
    ORDER BY rc.is_active DESC, c.name
";
$user_classes = $conn->query($classes_query);

// دریافت پرداخت‌های اخیر
$payments_query = "
    SELECT * FROM payments 
    WHERE registration_id = $customer_id 
    ORDER BY payment_date DESC 
    LIMIT 5
";
$recent_payments = $conn->query($payments_query);

// دریافت آمار حضور و غیاب
$attendance_stats_query = "
    SELECT 
        COUNT(*) as total_sessions,
        COUNT(CASE WHEN status = 'present' THEN 1 END) as present_count,
        COUNT(CASE WHEN status = 'absent' THEN 1 END) as absent_count,
        COUNT(CASE WHEN status = 'late' THEN 1 END) as late_count
    FROM attendance 
    WHERE registration_id = $customer_id
";
$attendance_stats = $conn->query($attendance_stats_query)->fetch_assoc();

// محاسبه درصد حضور
$attendance_percentage = 0;
if ($attendance_stats['total_sessions'] > 0) {
    $attendance_percentage = round(($attendance_stats['present_count'] / $attendance_stats['total_sessions']) * 100);
}

// دریافت کلاس‌های آینده (برنامه هفتگی)
$upcoming_classes_query = "
    SELECT c.name, c.schedule, c.duration, i.first_name as instructor_name, i.last_name as instructor_lastname
    FROM registration_classes rc
    JOIN classes c ON rc.class_id = c.id
    LEFT JOIN instructors i ON c.instructor = CONCAT(i.first_name, ' ', i.last_name)
    WHERE rc.registration_id = $customer_id AND rc.is_active = 1 AND c.is_active = 1
";
$upcoming_classes = $conn->query($upcoming_classes_query);

// تابع فرمت کردن مبلغ
function format_price($amount) {
    return number_format($amount) . ' تومان';
}

// تابع تبدیل تاریخ
function persian_date($timestamp) {
    return date('Y/m/d', strtotime($timestamp));
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل کاربری - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Tahoma", "Arial", sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 15px;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .user-status {
            font-size: 14px;
            opacity: 0.8;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-nav li {
            margin-bottom: 5px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-right-color: #fff;
        }

        .sidebar-nav i {
            margin-left: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-right: 280px;
            padding: 20px;
        }

        .main-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .welcome-message {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .welcome-text p {
            color: #666;
        }

        .current-time {
            text-align: left;
            color: #666;
            font-size: 14px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 24px;
        }

        .stat-icon.classes { background-color: rgba(102, 126, 234, 0.1); color: #667eea; }
        .stat-icon.attendance { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
        .stat-icon.payments { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }

        .stat-content h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-content p {
            color: #666;
            font-size: 14px;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }

        .dashboard-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            font-size: 18px;
            color: #333;
        }

        .card-header i {
            margin-left: 8px;
            color: #667eea;
        }

        .card-content {
            padding: 20px;
        }

        /* Classes List */
        .class-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .class-info h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .class-info p {
            font-size: 14px;
            color: #666;
            margin-bottom: 3px;
        }

        .class-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        /* Schedule */
        .schedule-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-right: 4px solid #667eea;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .schedule-time {
            width: 80px;
            text-align: center;
            margin-left: 15px;
        }

        .schedule-day {
            font-weight: bold;
            font-size: 14px;
        }

        .schedule-hour {
            color: #666;
            font-size: 12px;
        }

        .schedule-info h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .schedule-instructor {
            color: #666;
            font-size: 14px;
        }

        /* Payments */
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .payment-info h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .payment-info p {
            font-size: 14px;
            color: #666;
        }

        .payment-amount {
            font-weight: bold;
            color: #28a745;
        }

        .payment-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-completed {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-right: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .welcome-message {
                flex-direction: column;
                align-items: flex-start;
            }

            .current-time {
                margin-top: 10px;
                text-align: right;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-dumbbell"></i> آرین رزم</h2>
                <p>پنل هنرجو</p>
            </div>
            
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                <div class="user-status">هنرجوی فعال</div>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#" class="active"><i class="fas fa-home"></i> داشبورد</a></li>
                    <li><a href="customer-profile.php"><i class="fas fa-user"></i> پروفایل من</a></li>
                    <li><a href="customer-classes.php"><i class="fas fa-dumbbell"></i> کلاس‌های من</a></li>
                    <li><a href="customer-schedule.php"><i class="fas fa-calendar-alt"></i> برنامه کلاسی</a></li>
                    <li><a href="customer-attendance.php"><i class="fas fa-calendar-check"></i> حضور و غیاب</a></li>
                    <li><a href="customer-payments.php"><i class="fas fa-credit-card"></i> پرداخت‌ها</a></li>
                    <li><a href="customer-messages.php"><i class="fas fa-envelope"></i> پیام‌ها</a></li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    خروج از حساب
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <div class="welcome-message">
                    <div class="welcome-text">
                        <h1>خوش آمدید، <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
                        <p>به پنل کاربری باشگاه ورزشی آرین رزم خوش آمدید</p>
                    </div>
                    <div class="current-time">
                        <i class="fas fa-clock"></i>
                        <?php echo date('Y/m/d - H:i'); ?>
                    </div>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon classes">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $user_classes->num_rows; ?></h3>
                        <p>کلاس‌های ثبت نام شده</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon attendance">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $attendance_percentage; ?>%</h3>
                        <p>درصد حضور</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon payments">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $recent_payments->num_rows; ?></h3>
                        <p>پرداخت‌های انجام شده</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- My Classes -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-dumbbell"></i> کلاس‌های من</h3>
                    </div>
                    <div class="card-content">
                        <?php if ($user_classes->num_rows > 0): ?>
                            <?php 
                            $user_classes->data_seek(0); // Reset pointer
                            while ($class = $user_classes->fetch_assoc()): 
                            ?>
                            <div class="class-item">
                                <div class="class-info">
                                    <h4><?php echo htmlspecialchars($class['name']); ?></h4>
                                    <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($class['schedule']); ?></p>
                                    <p><i class="fas fa-hourglass-half"></i> <?php echo htmlspecialchars($class['duration']); ?></p>
                                </div>
                                <div>
                                    <span class="class-status <?php echo $class['enrollment_active'] ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $class['enrollment_active'] ? 'فعال' : 'غیرفعال'; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="no-data">شما در هیچ کلاسی ثبت نام نکرده‌اید.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Weekly Schedule -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-calendar-alt"></i> برنامه هفتگی</h3>
                    </div>
                    <div class="card-content">
                        <?php if ($upcoming_classes->num_rows > 0): ?>
                            <?php while ($schedule = $upcoming_classes->fetch_assoc()): ?>
                            <div class="schedule-item">
                                <div class="schedule-info">
                                    <h4><?php echo htmlspecialchars($schedule['name']); ?></h4>
                                    <p class="schedule-instructor">
                                        <i class="fas fa-user"></i>
                                        <?php echo htmlspecialchars($schedule['instructor_name'] . ' ' . $schedule['instructor_lastname']); ?>
                                    </p>
                                    <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($schedule['schedule']); ?></p>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="no-data">برنامه کلاسی تعریف نشده است.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-credit-card"></i> پرداخت‌های اخیر</h3>
                    </div>
                    <div class="card-content">
                        <?php if ($recent_payments->num_rows > 0): ?>
                            <?php while ($payment = $recent_payments->fetch_assoc()): ?>
                            <div class="payment-item">
                                <div class="payment-info">
                                    <h4><?php echo htmlspecialchars($payment['description'] ?? 'پرداخت شهریه'); ?></h4>
                                    <p><?php echo persian_date($payment['payment_date']); ?></p>
                                    <span class="payment-status status-<?php echo $payment['status']; ?>">
                                        <?php 
                                        $status_labels = [
                                            'completed' => 'تکمیل شده',
                                            'pending' => 'در انتظار',
                                            'failed' => 'ناموفق'
                                        ];
                                        echo $status_labels[$payment['status']] ?? $payment['status'];
                                        ?>
                                    </span>
                                </div>
                                <div class="payment-amount">
                                    <?php echo format_price($payment['amount']); ?>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="no-data">هیچ پرداختی انجام نشده است.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Attendance Summary -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-pie"></i> خلاصه حضور و غیاب</h3>
                    </div>
                    <div class="card-content">
                        <?php if ($attendance_stats['total_sessions'] > 0): ?>
                            <div class="attendance-summary">
                                <div class="attendance-item">
                                    <span class="attendance-label">کل جلسات:</span>
                                    <span class="attendance-value"><?php echo $attendance_stats['total_sessions']; ?></span>
                                </div>
                                <div class="attendance-item">
                                    <span class="attendance-label">حضور:</span>
                                    <span class="attendance-value text-success"><?php echo $attendance_stats['present_count']; ?></span>
                                </div>
                                <div class="attendance-item">
                                    <span class="attendance-label">غیبت:</span>
                                    <span class="attendance-value text-danger"><?php echo $attendance_stats['absent_count']; ?></span>
                                </div>
                                <div class="attendance-item">
                                    <span class="attendance-label">تأخیر:</span>
                                    <span class="attendance-value text-warning"><?php echo $attendance_stats['late_count']; ?></span>
                                </div>
                                <div class="attendance-percentage">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo $attendance_percentage; ?>%"></div>
                                    </div>
                                    <p>درصد حضور: <?php echo $attendance_percentage; ?>%</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="no-data">هنوز حضور و غیابی ثبت نشده است.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .attendance-summary {
            space-y: 10px;
        }

        .attendance-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .attendance-item:last-child {
            border-bottom: none;
        }

        .attendance-label {
            font-weight: bold;
        }

        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }

        .attendance-percentage {
            margin-top: 15px;
            text-align: center;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
    </style>
</body>
</html>

<?php $conn->close(); ?>
