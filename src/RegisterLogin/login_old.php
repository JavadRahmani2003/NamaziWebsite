<?php
session_start();
require_once '../modules/database.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = $_POST['mobile'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($mobile && $password) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $mobile = $conn->real_escape_string($mobile);
        
        $query = "SELECT id, first_name, last_name, mobile, password, status FROM registrations WHERE mobile = '$mobile' AND status = 'approved'";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // بررسی رمز عبور (در صورتی که هش شده باشد از password_verify استفاده کنید)
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['customer_id'] = $user['id'];
                $_SESSION['customer_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['customer_mobile'] = $user['mobile'];
                
                header('Location: customer-dashboard.php');
                exit;
            } else {
                $error_message = 'رمز عبور اشتباه است.';
            }
        } else {
            $error_message = 'کاربری با این شماره موبایل یافت نشد یا حساب شما هنوز تایید نشده است.';
        }
        
        $conn->close();
    } else {
        $error_message = 'لطفاً تمام فیلدها را پر کنید.';
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود هنرجویان - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Tahoma", "Arial", sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon input {
            padding-right: 45px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo i {
            font-size: 48px;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-dumbbell"></i>
        </div>
        
        <div class="login-header">
            <h1>باشگاه ورزشی آرین رزم</h1>
            <p>ورود هنرجویان</p>
        </div>

        <?php if ($error_message): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="mobile">شماره موبایل:</label>
                <div class="input-with-icon">
                    <input type="text" id="mobile" name="mobile" placeholder="09123456789" required 
                           value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>">
                    <i class="fas fa-mobile-alt"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">رمز عبور:</label>
                <div class="input-with-icon">
                    <input type="password" id="password" name="password" placeholder="رمز عبور خود را وارد کنید" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i>
                ورود
            </button>
        </form>

        <div class="register-link">
            <p>هنوز ثبت نام نکرده‌اید؟ <a href="register.php">ثبت نام کنید</a></p>
        </div>
    </div>
</body>
</html>
