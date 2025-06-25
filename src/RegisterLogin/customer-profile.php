<?php
session_start();
require_once 'includes/database.php';

// بررسی ورود کاربر
if (!isset($_SESSION['customer_id'])) {
    header('Location: customer-login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$customer_id = $_SESSION['customer_id'];

$success_message = '';
$error_message = '';

// پردازش ویرایش پروفایل
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $address = $conn->real_escape_string($_POST['address']);
    $emergency_contact = $conn->real_escape_string($_POST['emergency_contact'] ?? '');
    
    $update_query = "
        UPDATE registrations SET 
            first_name = '$first_name',
            last_name = '$last_name',
            email = '$email',
            phone = '$phone',
            address = '$address',
            emergency_contact = '$emergency_contact',
            updated_at = NOW()
        WHERE id = $customer_id
    ";
    
    if ($conn->query($update_query)) {
        $success_message = 'اطلاعات شما با موفقیت به‌روزرسانی شد.';
        $_SESSION['customer_name'] = $first_name . ' ' . $last_name;
    } else {
        $error_message = 'خطا در به‌روزرسانی اطلاعات.';
    }
}

// دریافت اطلاعات کاربر
$user_query = "SELECT * FROM registrations WHERE id = $customer_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل من - باشگاه ورزشی آرین رزم</title>
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

        /* Sidebar (same as customer-dashboard.php) */
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

        .profile-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin: 0 auto 20px;
        }

        .profile-form {
            padding: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .readonly {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .btn i {
            margin-left: 8px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .info-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-section h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #666;
        }

        .info-value {
            color: #333;
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

            .form-grid {
                grid-template-columns: 1fr;
            }

            .profile-header {
                padding: 20px;
            }

            .profile-avatar {
                width: 80px;
                height: 80px;
                font-size: 32px;
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
                    <li><a href="customer-dashboard.php"><i class="fas fa-home"></i> داشبورد</a></li>
                    <li><a href="customer-profile.php" class="active"><i class="fas fa-user"></i> پروفایل من</a></li>
                    <li><a href="customer-classes.php"><i class="fas fa-dumbbell"></i> کلاس‌های من</a></li>
                    <li><a href="customer-schedule.php"><i class="fas fa-calendar-alt"></i> برنامه کلاسی</a></li>
                    <li><a href="customer-attendance.php"><i class="fas fa-calendar-check"></i> حضور و غیاب</a></li>
                    <li><a href="customer-payments.php"><i class="fas fa-credit-card"></i> پرداخت‌ها</a></li>
                    <li><a href="customer-messages.php"><i class="fas fa-envelope"></i> پیام‌ها</a></li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="customer-logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    خروج از حساب
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>پروفایل من</h1>
                <p>مشاهده و ویرایش اطلاعات شخصی</p>
            </header>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <p>عضو از تاریخ: <?php echo date('Y/m/d', strtotime($user['registration_date'])); ?></p>
                </div>

                <div class="profile-form">
                    <!-- اطلاعات غیرقابل تغییر -->
                    <div class="info-section">
                        <h3>اطلاعات ثبت نام</h3>
                        <div class="info-item">
                            <span class="info-label">کد ملی:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['national_code']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">تاریخ تولد:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['birth_date']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">جنسیت:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['gender']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">شماره موبایل:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['mobile']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">وضعیت حساب:</span>
                            <span class="info-value">
                                <?php 
                                $status_labels = [
                                    'pending' => 'در انتظار تایید',
                                    'approved' => 'تایید شده',
                                    'rejected' => 'رد شده'
                                ];
                                echo $status_labels[$user['status']] ?? $user['status'];
                                ?>
                            </span>
                        </div>
                    </div>

                    <!-- فرم ویرایش اطلاعات -->
                    <form method="POST">
                        <h3 style="margin-bottom: 20px;">ویرایش اطلاعات</h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first_name">نام:</label>
                                <input type="text" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">نام خانوادگی:</label>
                                <input type="text" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email">ایمیل:</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">تلفن ثابت:</label>
                                <input type="text" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="emergency_contact">شماره تماس اضطراری:</label>
                                <input type="text" id="emergency_contact" name="emergency_contact" 
                                       value="<?php echo htmlspecialchars($user['emergency_contact'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="education">تحصیلات:</label>
                                <input type="text" id="education" name="education" 
                                       value="<?php echo htmlspecialchars($user['education'] ?? ''); ?>" readonly class="readonly">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">آدرس:</label>
                            <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>

                        <div style="text-align: center; margin-top: 30px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                ذخیره تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

<?php $conn->close(); ?>
