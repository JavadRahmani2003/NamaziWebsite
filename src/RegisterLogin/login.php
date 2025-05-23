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
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">نام کاربری یا ایمیل</label>
                    <div class="input-group">
                        <input type="text" id="username" name="username" required>
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="error-message">لطفاً نام کاربری یا ایمیل خود را وارد کنید</div>
                </div>
                
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="error-message">لطفاً رمز عبور خود را وارد کنید</div>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="rememberMe" name="rememberMe">
                        <label for="rememberMe">مرا به خاطر بسپار</label>
                    </div>
                    <a href="#" class="forgot-password">رمز عبور را فراموش کرده‌اید؟</a>
                </div>
                
                <button type="submit" class="login-btn">ورود به حساب کاربری</button>
            </form>
        </div>
        
        <div class="login-footer">
            <p>حساب کاربری ندارید؟ <a href="register.html" class="register-link">ثبت نام کنید</a></p>
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
        const loginForm = document.getElementById('loginForm');
        
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            
            // Validate username
            const username = document.getElementById('username');
            if (!username.value.trim()) {
                username.closest('.form-group').classList.add('error');
                isValid = false;
            } else {
                username.closest('.form-group').classList.remove('error');
            }
            
            // Validate password
            const password = document.getElementById('password');
            if (!password.value.trim()) {
                password.closest('.form-group').classList.add('error');
                isValid = false;
            } else {
                password.closest('.form-group').classList.remove('error');
            }
            
            // If form is valid, redirect to dashboard
            if (isValid) {
                // In a real application, you would send the form data to the server for authentication
                // For demo purposes, we'll just redirect to the dashboard
                window.location.href = 'dashboard.html';
            }
        });
        
        // Clear error on input
        loginForm.querySelectorAll('input').forEach(function(input) {
            input.addEventListener('input', function() {
                this.closest('.form-group').classList.remove('error');
            });
        });
        
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