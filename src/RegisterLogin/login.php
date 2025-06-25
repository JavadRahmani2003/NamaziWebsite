<?php
session_start();
require_once '../modules/database.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $email = $conn->real_escape_string($email);
        
        $query = "SELECT id, first_name, last_name, mobile, email password, status FROM registrations WHERE email = '$mobile' AND status = 'approved'";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // بررسی رمز عبور (در صورتی که هش شده باشد از password_verify استفاده کنید)
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['customer_id'] = $user['id'];
                $_SESSION['customer_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['customer_email'] = $user['email'];
                
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
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به حساب کاربری - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="loginstyle.css">
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
    }
    window.onload = loadMenu;
    </script>
</head>
<body dir="rtl" id="lighttheme">

<!-- منوی اصلی -->
<nav id="mysiteMenu"></nav>
    
<div class="login-container">
    

    <div class="login-card">
        <div class="login-header">
            <h2>ورود به حساب کاربری</h2>
            <p>به باشگاه ورزشی آرین رزم خوش آمدید</p>
        </div>
        <div class="login-body">
            <?php
            $error = [];

            require_once '../modules/database.php';
            $conn = new Database();
            $conn->getConnection();

            // اگر قبلاً وارد شده، انتقال به داشبورد
            if (isset($_SESSION['user_id'])) {
                header('location: customer-dashboard.php');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $email = trim($_POST['emailaddr']);
                $password = $_POST['password'];

                if (empty($email) || empty($password)) {
                    $error[] = "لطفا ایمیل و رمز عبور را وارد کنید.";
                } else {
                    $stmt = $conn->prepare("SELECT * FROM registrations WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        if (password_verify($password,$user['password'])) {
                            // ورود موفق
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['email'] = $user['email'];
                            $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
                            // ثبت آخرین ورود
                            $stmt = $conn->prepare("UPDATE registrations SET updated_at = NOW() WHERE id = ?");
                            $stmt->bind_param("i", $user['id']);
                            $stmt->execute();
                            header('location: dashboard.php');
                            exit;
                        } else {
                            $error[] = "رمز عبور اشتباه است.";
                        }
                    } else {
                        $error[] = "کاربری با این ایمیل یافت نشد.";
                    }
                }
            }
            $conn->close();
            ?>
            <?php if (!empty($error)): ?>
                <div class="error-box">
                    <?php foreach ($error as $msg): ?>
                        <p><?php echo htmlspecialchars($msg); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label for="username">ایمیل</label>
                    <div class="input-group">
                        <input type="text" id="username" name="emailaddr">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="error-message">لطفاً ایمیل خود را وارد کنید</div>
                </div>
                
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="error-message">لطفاً رمز عبور خود را وارد کنید</div>
                </div>
                                
                <button type="submit" class="login-btn">ورود به حساب کاربری</button>
            </form>
        </div>
        
        <div class="login-footer">
            <p>حساب کاربری ندارید؟ <a href="register.php" class="register-link">ثبت نام کنید</a></p>
            <div class="social-login">
                <a href="#" class="social-btn google"><i class="fab fa-google"></i></a>
                <a href="#" class="social-btn telegram"><i class="fab fa-telegram-plane"></i></a>
                <a href="#" class="social-btn instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>

<script src="../indexScript.js"></script>

<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        // const loginForm = document.getElementById('loginForm');
        
        // loginForm.addEventListener('submit', function(e) {
        //     e.preventDefault();
            
        //     let isValid = true;
            
        //     // Validate username
        //     const username = document.getElementById('username');
        //     if (!username.value.trim()) {
        //         username.closest('.form-group').classList.add('error');
        //         isValid = false;
        //     } else {
        //         username.closest('.form-group').classList.remove('error');
        //     }
            
        //     // Validate password
        //     const password = document.getElementById('password');
        //     if (!password.value.trim()) {
        //         password.closest('.form-group').classList.add('error');
        //         isValid = false;
        //     } else {
        //         password.closest('.form-group').classList.remove('error');
        //     }
            
        //     // If form is valid, redirect to dashboard
        //     if (isValid) {
        //         // In a real application, you would send the form data to the server for authentication
        //         // For demo purposes, we'll just redirect to the dashboard
        //         window.location.href = 'dashboard.php';
        //     }
        // });
        
        // Clear error on input
        // loginForm.querySelectorAll('input').forEach(function(input) {
        //     input.addEventListener('input', function() {
        //         this.closest('.form-group').classList.remove('error');
        //     });
        // });
        
        // Toggle theme button
        const themeToggle = document.createElement('div');
        themeToggle.className = 'theme-toggle';
        themeToggle.style.position = 'absolute';
        themeToggle.style.top = '20px';
        themeToggle.style.left = '20px';
        themeToggle.style.zIndex = '10';
        themeToggle.style.cursor = 'pointer';
        themeToggle.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
        themeToggle.style.padding = '10px';
        themeToggle.style.borderRadius = '50%';
        themeToggle.style.color = 'white';
        themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        themeToggle.onclick = toggleTheme;
        
        document.querySelector('.login-container').appendChild(themeToggle);
        
        // Check saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.id = 'darktheme';
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
    });
</script>
</body>
</html>