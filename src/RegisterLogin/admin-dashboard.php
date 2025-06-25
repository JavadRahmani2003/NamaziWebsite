<?php
session_start();
require_once '../modules/database.php';

// بررسی ورود کاربر (در صورت نیاز)
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit;
// }

$db = new Database();
$conn = $db->getConnection();

// دریافت آمار کلی از ویو
$stats_query = "SELECT * FROM v_dashboard_stats";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// دریافت ثبت نام‌های اخیر
$recent_registrations_query = "
    SELECT id, first_name, last_name, mobile, status, registration_date 
    FROM registrations 
    ORDER BY registration_date DESC 
    LIMIT 5
";
$recent_registrations = $conn->query($recent_registrations_query);

// دریافت کلاس‌های فعال
$active_classes_query = "
    SELECT c.*, COUNT(rc.registration_id) as enrolled_count
    FROM classes c
    LEFT JOIN registration_classes rc ON c.id = rc.class_id AND rc.is_active = 1
    WHERE c.is_active = 1
    GROUP BY c.id
    ORDER BY c.name
";
$active_classes = $conn->query($active_classes_query);

// دریافت پرداخت‌های اخیر
$recent_payments_query = "
    SELECT p.*, CONCAT(r.first_name, ' ', r.last_name) as student_name
    FROM payments p
    JOIN registrations r ON p.registration_id = r.id
    WHERE p.status = 'completed'
    ORDER BY p.payment_date DESC
    LIMIT 5
";
$recent_payments = $conn->query($recent_payments_query);

// دریافت آمار ماهانه
$monthly_stats_query = "
    SELECT 
        MONTH(registration_date) as month,
        YEAR(registration_date) as year,
        COUNT(*) as registrations_count,
        COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_count
    FROM registrations 
    WHERE registration_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY YEAR(registration_date), MONTH(registration_date)
    ORDER BY year DESC, month DESC
";
$monthly_stats = $conn->query($monthly_stats_query);

// تابع تبدیل تاریخ میلادی به شمسی (ساده)
function persian_date($timestamp) {
    $months = [
        1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
        4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
        7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
        10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
    ];
    
    $date = date('Y-m-d', strtotime($timestamp));
    return $date; // برای سادگی تاریخ میلادی برگردانده می‌شود
}

// تابع فرمت کردن مبلغ
function format_price($amount) {
    return number_format($amount) . ' تومان';
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد مدیریت - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-dumbbell"></i>آرین رزم</h2>
                <p>پنل مدیریت</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#" class="active"><i class="fas fa-home"></i> داشبورد</a></li>
                    <li><a href="registrations.php"><i class="fas fa-user-plus"></i> ثبت نام‌ها</a></li>
                    <li><a href="classes.php"><i class="fas fa-dumbbell"></i> کلاس‌ها</a></li>
                    <li><a href="payments.php"><i class="fas fa-credit-card"></i> پرداخت‌ها</a></li>
                    <li><a href="instructors.php"><i class="fas fa-chalkboard-teacher"></i> مربیان</a></li>
                    <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> حضور و غیاب</a></li>
                    <li><a href="reports.php"><i class="fas fa-chart-bar"></i> گزارشات</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> تنظیمات</a></li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> خروج
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>داشبورد</h1>
                <div class="header-actions">
                    <button class="btn btn-primary" onclick="location.href='add-registration.php'">
                        <i class="fas fa-plus"></i> ثبت نام جدید
                    </button>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['pending_registrations']; ?></h3>
                        <p>ثبت نام‌های در انتظار</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check text-success"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['approved_registrations']; ?></h3>
                        <p>ثبت نام‌های تایید شده</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day text-info"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['today_registrations']; ?></h3>
                        <p>ثبت نام‌های امروز</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill text-primary"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo format_price($stats['today_income']); ?></h3>
                        <p>درآمد امروز</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Recent Registrations -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-plus"></i> ثبت نام‌های اخیر</h3>
                        <a href="registrations.php" class="view-all">مشاهده همه</a>
                    </div>
                    <div class="card-content">
                        <?php if ($recent_registrations->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>نام و نام خانوادگی</th>
                                            <th>موبایل</th>
                                            <th>وضعیت</th>
                                            <th>تاریخ ثبت نام</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $recent_registrations->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                                            <td>
                                                <span class="status status-<?php echo $row['status']; ?>">
                                                    <?php 
                                                    $status_labels = [
                                                        'pending' => 'در انتظار',
                                                        'approved' => 'تایید شده',
                                                        'rejected' => 'رد شده',
                                                        'cancelled' => 'لغو شده'
                                                    ];
                                                    echo $status_labels[$row['status']] ?? $row['status'];
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?php echo persian_date($row['registration_date']); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="no-data">هیچ ثبت نام اخیری وجود ندارد.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Active Classes -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-dumbbell"></i> کلاس‌های فعال</h3>
                        <a href="classes.php" class="view-all">مشاهده همه</a>
                    </div>
                    <div class="card-content">
                        <?php if ($active_classes->num_rows > 0): ?>
                            <div class="classes-list">
                                <?php while ($class = $active_classes->fetch_assoc()): ?>
                                <div class="class-item">
                                    <div class="class-info">
                                        <h4><?php echo htmlspecialchars($class['name']); ?></h4>
                                        <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($class['schedule']); ?></p>
                                        <p><i class="fas fa-users"></i> <?php echo $class['enrolled_count']; ?> / <?php echo $class['max_students']; ?> نفر</p>
                                    </div>
                                    <div class="class-price">
                                        <?php echo format_price($class['price']); ?>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data">هیچ کلاس فعالی وجود ندارد.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-credit-card"></i> پرداخت‌های اخیر</h3>
                        <a href="payments.php" class="view-all">مشاهده همه</a>
                    </div>
                    <div class="card-content">
                        <?php if ($recent_payments->num_rows > 0): ?>
                            <div class="payments-list">
                                <?php while ($payment = $recent_payments->fetch_assoc()): ?>
                                <div class="payment-item">
                                    <div class="payment-info">
                                        <h4><?php echo htmlspecialchars($payment['student_name']); ?></h4>
                                        <p><?php echo persian_date($payment['payment_date']); ?></p>
                                        <small><?php echo htmlspecialchars($payment['description'] ?? 'پرداخت شهریه'); ?></small>
                                    </div>
                                    <div class="payment-amount">
                                        <?php echo format_price($payment['amount']); ?>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-data">هیچ پرداخت اخیری وجود ندارد.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Monthly Chart -->
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-line"></i> آمار ماهانه ثبت نام‌ها</h3>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="monthlyChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Simple chart implementation
        const canvas = document.getElementById('monthlyChart');
        const ctx = canvas.getContext('2d');
        
        // Sample data from PHP
        const monthlyData = [
            <?php 
            $chart_data = [];
            if ($monthly_stats->num_rows > 0) {
                while ($row = $monthly_stats->fetch_assoc()) {
                    $chart_data[] = $row['registrations_count'];
                }
            }
            echo implode(',', array_reverse($chart_data));
            ?>
        ];
        
        // Draw simple bar chart
        function drawChart() {
            const maxValue = Math.max(...monthlyData);
            const barWidth = canvas.width / monthlyData.length;
            const barMaxHeight = canvas.height - 40;
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            monthlyData.forEach((value, index) => {
                const barHeight = (value / maxValue) * barMaxHeight;
                const x = index * barWidth;
                const y = canvas.height - barHeight - 20;
                
                ctx.fillStyle = '#e63946';
                ctx.fillRect(x + 10, y, barWidth - 20, barHeight);
                
                ctx.fillStyle = '#333';
                ctx.font = '12px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(value, x + barWidth/2, y - 5);
            });
        }
        
        drawChart();
    </script>
</body>
</html>

<?php
$conn->close();
?>
