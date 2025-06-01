<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="registerstyle.css">
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

<!-- Menu -->
<nav id="mysiteMenu"></nav>

<div class="MobileMenu" onclick="toggleMenu()">
    <span>منو</span>
</div>

<div class="bodyOfSite">
    <!-- Register Header -->
    <div class="register-header">
        <div class="container">
            <h1 class="register-title">ثبت نام در کلاس‌های باشگاه آرین رزم</h1>
            <p class="register-subtitle">برای ثبت نام در کلاس‌های باشگاه آرین رزم، لطفاً فرم زیر را با دقت تکمیل نمایید.</p>
        </div>
    </div>

    <!-- Register Form -->
    <div class="register-form-container">
        <?php
        // تعریف متغیر برای نمایش پیام موفقیت
        $showSuccessMessage = false;
        
        // بررسی ارسال فرم
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // بررسی وجود فیلدهای اجباری
            if (isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['nationalCode']) && 
                isset($_POST['birthDate']) && isset($_POST['gender']) && isset($_POST['mobile']) && 
                isset($_POST['address']) && isset($_POST['classes']) && isset($_POST['skillLevel']) && 
                isset($_POST['hasHealthIssue']) && isset($_POST['hasInjury']) && 
                isset($_POST['termsAgreement']) && isset($_POST['healthAgreement'])) {
                
                // دریافت داده‌های فرم
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $nationalCode = $_POST['nationalCode'];
                $birthDate = $_POST['birthDate'];
                $gender = $_POST['gender'];
                $education = isset($_POST['education']) ? $_POST['education'] : '';
                $mobile = $_POST['mobile'];
                $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
                $email = isset($_POST['email']) ? $_POST['email'] : '';
                $emergencyContact = isset($_POST['emergencyContact']) ? $_POST['emergencyContact'] : '';
                $address = $_POST['address'];
                $classes = $_POST['classes']; // این یک آرایه است
                $skillLevel = $_POST['skillLevel'];
                $experience = isset($_POST['experience']) ? $_POST['experience'] : '';
                $hasHealthIssue = $_POST['hasHealthIssue'];
                $healthDetails = ($hasHealthIssue == 'yes' && isset($_POST['healthDetails'])) ? $_POST['healthDetails'] : '';
                $hasInjury = $_POST['hasInjury'];
                $injuryDetails = ($hasInjury == 'yes' && isset($_POST['injuryDetails'])) ? $_POST['injuryDetails'] : '';
                $referral = isset($_POST['referral']) ? $_POST['referral'] : '';
                $goal = isset($_POST['goal']) ? $_POST['goal'] : '';
                $comments = isset($_POST['comments']) ? $_POST['comments'] : '';
                $newsletterAgreement = isset($_POST['newsletterAgreement']) ? 1 : 0;
                $reg_date = date("Y/m/d H:i:s");

                // تبدیل آرایه کلاس‌ها به رشته
                if (is_array($classes)) {
                    $classesStr = implode(', ', $classes);
                } else {
                    $classesStr = $classes;
                }
                
                require_once '../modules/database.php';

                // اتصال به پایگاه داده
                try {
                    $conn = new Database();
                    $conn->getConnection();
                    
                    // آماده‌سازی و اجرای کوئری
                    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, national_code, birth_date, 
                        gender, education, mobile, phone, email, emergency_contact, address, classes, skill_level, 
                        experience, has_health_issue, health_details, has_injury, injury_details, referral, goal, 
                        comments, newsletter_agreement, registration_date) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    $stmt->bind_param('sssssssssssssssssssssss', $firstName, $lastName, $nationalCode, $birthDate, $gender, $education, $mobile,
                    $phone, $email, $emergencyContact, $address, $classesStr, $skillLevel, $experience, $hasHealthIssue, $healthDetails, $hasInjury,
                    $injuryDetails, $referral, $goal, $comments, $newsletterAgreement, $reg_date);
                    
                    $stmt->execute();
                    
                    // نمایش پیام موفقیت
                    $showSuccessMessage = true;
                    
                    // ارسال ایمیل به مدیر (اختیاری)
                    // mail("admin@arianrazm.com", "ثبت نام جدید", "یک ثبت نام جدید توسط $firstName $lastName انجام شد.");
                    
                } catch(PDOException $e) {
                    echo '<div class="error-message" style="display:block;margin-bottom:20px;">خطا در ثبت اطلاعات: ' . $e->getMessage() . '</div>';
                }
            }
        }
        ?>
        
        <!-- پیام موفقیت -->
        <div class="success-message" id="successMessage" style="display: <?php echo $showSuccessMessage ? 'block' : 'none'; ?>">
            <i class="fas fa-check-circle"></i> ثبت نام شما با موفقیت انجام شد. کارشناسان ما به زودی با شما تماس خواهند گرفت.
        </div>

        <?php if (!$showSuccessMessage): ?>
        <form class="register-form" id="registerForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-user"></i> اطلاعات شخصی</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName" class="required-field">نام</label>
                        <input type="text" id="firstName" name="firstName" required>
                        <div class="error-message">لطفاً نام خود را وارد کنید</div>
                    </div>
                    <div class="form-group">
                        <label for="lastName" class="required-field">نام خانوادگی</label>
                        <input type="text" id="lastName" name="lastName" required>
                        <div class="error-message">لطفاً نام خانوادگی خود را وارد کنید</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nationalCode" class="required-field">کد ملی</label>
                        <input type="text" id="nationalCode" name="nationalCode" required>
                        <div class="error-message">لطفاً کد ملی معتبر وارد کنید</div>
                    </div>
                    <div class="form-group">
                        <label for="birthDate" class="required-field">تاریخ تولد</label>
                        <input type="text" id="birthDate" name="birthDate" placeholder="مثال: 1380/06/15" required>
                        <div class="error-message">لطفاً تاریخ تولد خود را وارد کنید</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required-field">جنسیت</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="male" name="gender" value="مرد" required>
                                <label for="male">مرد</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="female" name="gender" value="زن">
                                <label for="female">زن</label>
                            </div>
                        </div>
                        <div class="error-message">لطفاً جنسیت خود را انتخاب کنید</div>
                    </div>
                    <div class="form-group">
                        <label for="education">تحصیلات</label>
                        <select id="education" name="education">
                            <option value="">انتخاب کنید</option>
                            <option value="بدون تحصیلات">تحصیلات ندارم</option>
                            <option value="ابتدایی">ابتدایی</option>
                            <option value="دیپلم">دیپلم</option>
                            <option value="فوق دیپلم">فوق دیپلم</option>
                            <option value="لیسانس">کارشناسی</option>
                            <option value="فوق لیسانس">کارشناسی ارشد</option>
                            <option value="دکتری">دکتری</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-phone"></i> اطلاعات تماس</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="mobile" class="required-field">شماره موبایل</label>
                        <input type="tel" id="mobile" name="mobile" required>
                        <div class="error-message">لطفاً شماره موبایل معتبر وارد کنید</div>
                    </div>
                    <div class="form-group">
                        <label for="phone">شماره ثابت</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="required-field">ایمیل</label>
                        <input type="email" id="email" name="email" required>
                        <div class="error-message">لطفاً ایمیل معتبر وارد کنید</div>
                    </div>
                    <div class="form-group">
                        <label for="emergencyContact">شماره تماس اضطراری</label>
                        <input type="tel" id="emergencyContact" name="emergencyContact">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="address" class="required-field">آدرس</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                        <div class="error-message">لطفاً آدرس خود را وارد کنید</div>
                    </div>
                </div>
            </div>

            <!-- Course Selection -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-dumbbell"></i> انتخاب کلاس</h3>
                
                <div class="class-card" onclick="selectClass(this, 'tekongmosoul')">
                    <div class="class-header">
                        <div class="class-title">دفاع شخصی</div>
                        <div class="class-price">۶۵۰,۰۰۰ تومان / ماه</div>
                    </div>
                    <p>برنامه‌های ویژه دفاع شخصی برای ارتقای توان بدنی و استقامت</p>
                    <div class="class-details">
                        <div class="class-detail"><i class="fas fa-calendar"></i> شنبه تا پنج‌شنبه</div>
                        <div class="class-detail"><i class="fas fa-clock"></i> 21:00 - 22:00</div>
                        <div class="class-detail"><i class="fas fa-users"></i> مناسب برای همه سنین</div>
                    </div>
                    <input type="checkbox" name="classes[]" value="tekongmosoul" style="display: none;">
                </div>
                
                <div class="class-card" onclick="selectClass(this, 'fitness')">
                    <div class="class-header">
                        <div class="class-title">آمادگی جسمانی</div>
                        <div class="class-price">۶۵۰,۰۰۰ تومان / ماه</div>
                    </div>
                    <p>برنامه‌های ویژه آمادگی جسمانی برای ارتقای توان بدنی و استقامت</p>
                    <div class="class-details">
                        <div class="class-detail"><i class="fas fa-calendar"></i> شنبه تا پنج‌شنبه</div>
                        <div class="class-detail"><i class="fas fa-clock"></i> 20:00 - 21:30</div>
                        <div class="class-detail"><i class="fas fa-users"></i> مناسب برای همه سنین</div>
                    </div>
                    <input type="checkbox" name="classes[]" value="fitness" style="display: none;">
                </div>
                
                <div class="class-card" onclick="selectClass(this, 'kids')">
                    <div class="class-header">
                        <div class="class-title">کلاس کودکان</div>
                        <div class="class-price">۷۰۰,۰۰۰ تومان / ماه</div>
                    </div>
                    <p>آموزش هنرهای رزمی به کودکان با روش‌های سرگرم‌کننده و آموزنده</p>
                    <div class="class-details">
                        <div class="class-detail"><i class="fas fa-calendar"></i> یکشنبه و سه‌شنبه</div>
                        <div class="class-detail"><i class="fas fa-clock"></i> 16:00 - 17:30</div>
                        <div class="class-detail"><i class="fas fa-users"></i> مناسب برای 5 تا 12 سال</div>
                    </div>
                    <input type="checkbox" name="classes[]" value="kids" style="display: none;">
                </div>
                
                <div class="error-message" id="classError" style="margin-top: 15px;">لطفاً حداقل یک کلاس را انتخاب کنید</div>
            </div>

            <!-- Experience Level -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-medal"></i> سطح مهارت</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required-field">سطح مهارت شما در هنرهای رزمی</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="beginner" name="skillLevel" value="beginner" required>
                                <label for="beginner">مبتدی (بدون تجربه قبلی)</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="intermediate" name="skillLevel" value="intermediate">
                                <label for="intermediate">متوسط (1 تا 3 سال تجربه)</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="advanced" name="skillLevel" value="advanced">
                                <label for="advanced">پیشرفته (بیش از 3 سال تجربه)</label>
                            </div>
                        </div>
                        <div class="error-message">لطفاً سطح مهارت خود را انتخاب کنید</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="experience">توضیحات تکمیلی درباره تجربیات قبلی</label>
                        <textarea id="experience" name="experience" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Health Information -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-heartbeat"></i> اطلاعات سلامتی</h3>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label class="required-field">آیا سابقه بیماری خاصی دارید؟</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="healthYes" name="hasHealthIssue" value="yes" required>
                                <label for="healthYes">بله</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="healthNo" name="hasHealthIssue" value="no">
                                <label for="healthNo">خیر</label>
                            </div>
                        </div>
                        <div class="error-message">لطفاً این گزینه را انتخاب کنید</div>
                    </div>
                </div>
                <div class="form-row" id="healthDetailsRow" style="display: none;">
                    <div class="form-group full-width">
                        <label for="healthDetails">توضیحات بیماری</label>
                        <textarea id="healthDetails" name="healthDetails" rows="3"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label class="required-field">آیا سابقه آسیب‌دیدگی یا جراحی دارید؟</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="injuryYes" name="hasInjury" value="yes" required>
                                <label for="injuryYes">بله</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="injuryNo" name="hasInjury" value="no">
                                <label for="injuryNo">خیر</label>
                            </div>
                        </div>
                        <div class="error-message">لطفاً این گزینه را انتخاب کنید</div>
                    </div>
                </div>
                <div class="form-row" id="injuryDetailsRow" style="display: none;">
                    <div class="form-group full-width">
                        <label for="injuryDetails">توضیحات آسیب‌دیدگی</label>
                        <textarea id="injuryDetails" name="injuryDetails" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-info-circle"></i> اطلاعات تکمیلی</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="referral">نحوه آشنایی با باشگاه</label>
                        <select id="referral" name="referral">
                            <option value="">انتخاب کنید</option>
                            <option value="friends">دوستان و آشنایان</option>
                            <option value="social">شبکه‌های اجتماعی</option>
                            <option value="website">وب‌سایت</option>
                            <option value="ads">تبلیغات</option>
                            <option value="other">سایر</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="goal">هدف شما از شرکت در کلاس‌ها</label>
                        <select id="goal" name="goal">
                            <option value="">انتخاب کنید</option>
                            <option value="fitness">آمادگی جسمانی</option>
                            <option value="selfDefense">دفاع شخصی</option>
                            <option value="competition">شرکت در مسابقات</option>
                            <option value="hobby">سرگرمی و علاقه شخصی</option>
                            <option value="other">سایر</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="comments">توضیحات تکمیلی</label>
                        <textarea id="comments" name="comments" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="form-section" dir="rtl">
                <h3 class="form-section-title"><i class="fas fa-file-contract"></i> قوانین و مقررات</h3>
                <div class="form-row">
                    <div class="form-group full-width">
                        <div class="checkbox-item">
                            <input type="checkbox" id="termsAgreement" name="termsAgreement" required>
                            <label for="termsAgreement" class="required-field">قوانین و مقررات باشگاه را مطالعه کرده و می‌پذیرم</label>
                        </div>
                        <div class="error-message">پذیرش قوانین و مقررات الزامی است</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <div class="checkbox-item">
                            <input type="checkbox" id="healthAgreement" name="healthAgreement" required>
                            <label for="healthAgreement" class="required-field">تأیید می‌کنم که از نظر جسمانی آمادگی شرکت در کلاس‌های ورزشی را دارم</label>
                        </div>
                        <div class="error-message">تأیید این گزینه الزامی است</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <div class="checkbox-item">
                            <input type="checkbox" id="newsletterAgreement" name="newsletterAgreement">
                            <label for="newsletterAgreement">مایل به دریافت اخبار و اطلاعیه‌های باشگاه هستم</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="submit-container">
                <button type="submit" class="register-btn">ثبت نام</button>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer id="footersite"></footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>
</div>

<script src="../indexScript.js"></script>
<script>
    // Form validation and submission
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide health details based on selection
        document.querySelectorAll('input[name="hasHealthIssue"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const healthDetailsRow = document.getElementById('healthDetailsRow');
                if (this.value === 'yes') {
                    healthDetailsRow.style.display = 'flex';
                } else {
                    healthDetailsRow.style.display = 'none';
                }
            });
        });

        // Show/hide injury details based on selection
        document.querySelectorAll('input[name="hasInjury"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const injuryDetailsRow = document.getElementById('injuryDetailsRow');
                if (this.value === 'yes') {
                    injuryDetailsRow.style.display = 'flex';
                } else {
                    injuryDetailsRow.style.display = 'none';
                }
            });
        });

        // Form validation
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validate required fields
                const requiredFields = registerForm.querySelectorAll('[required]');
                requiredFields.forEach(function(field) {
                    const formGroup = field.closest('.form-group');
                    if (!field.value.trim() || (field.type === 'checkbox' && !field.checked) || (field.type === 'radio' && !document.querySelector(`input[name="${field.name}"]:checked`))) {
                        if (formGroup) {
                            formGroup.classList.add('error');
                        }
                        isValid = false;
                    } else {
                        if (formGroup) {
                            formGroup.classList.remove('error');
                        }
                    }
                });
                
                // Validate class selection
                const selectedClasses = document.querySelectorAll('input[name="classes[]"]:checked');
                if (selectedClasses.length === 0) {
                    document.getElementById('classError').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('classError').style.display = 'none';
                }
                
                // اگر فرم معتبر نیست، از ارسال جلوگیری کن
                if (!isValid) {
                    e.preventDefault();
                    // اسکرول به اولین خطا
                    const firstError = document.querySelector('.form-group.error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
            
            // Clear error on input
            registerForm.querySelectorAll('input, select, textarea').forEach(function(field) {
                field.addEventListener('input', function() {
                    const formGroup = this.closest('.form-group');
                    if (formGroup) {
                        formGroup.classList.remove('error');
                    }
                });
            });
        }
    });

    // Class selection
    function selectClass(element, classValue) {
        element.classList.toggle('selected');
        const checkbox = element.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        
        // Hide error message if at least one class is selected
        const selectedClasses = document.querySelectorAll('input[name="classes[]"]:checked');
        if (selectedClasses.length > 0) {
            document.getElementById('classError').style.display = 'none';
        }
    }
    
    // Back to top button
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'flex';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
        
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
</body>
</html>