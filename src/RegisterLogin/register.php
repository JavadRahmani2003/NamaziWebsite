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
            <p class="register-subtitle">برای ثبت نام در کلاس‌های باشگاه آرین رزم، لطفاً فرم زیر را با دقت تکمیل نمایید. پس از ثبت نام، کارشناسان ما در اسرع وقت با شما تماس خواهند گرفت.</p>
        </div>
    </div>

    <!-- Register Form -->
    <div class="register-form-container">
        <div class="success-message" id="successMessage">
            <i class="fas fa-check-circle"></i> ثبت نام شما با موفقیت انجام شد. کارشناسان ما به زودی با شما تماس خواهند گرفت.
        </div>
        
        <form class="register-form" id="registerForm">
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
                                <input type="radio" id="male" name="gender" value="male" required>
                                <label for="male">مرد</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="female" name="gender" value="female">
                                <label for="female">زن</label>
                            </div>
                        </div>
                        <div class="error-message">لطفاً جنسیت خود را انتخاب کنید</div>
                    </div>
                    <div class="form-group">
                        <label for="education">تحصیلات</label>
                        <select id="education" name="education">
                            <option value="">انتخاب کنید</option>
                            <option value="primary">ابتدایی</option>
                            <option value="diploma">دیپلم</option>
                            <option value="associate">فوق دیپلم</option>
                            <option value="bachelor">کارشناسی</option>
                            <option value="master">کارشناسی ارشد</option>
                            <option value="phd">دکتری</option>
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
                        <label for="email">ایمیل</label>
                        <input type="email" id="email" name="email">
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
                
                <div class="class-card" onclick="selectClass(this, 'karate')">
                    <div class="class-header">
                        <div class="class-title">کاراته</div>
                        <div class="class-price">۸۰۰,۰۰۰ تومان / ماه</div>
                    </div>
                    <p>آموزش تخصصی کاراته زیر نظر استاد مهدی نمازی با بیش از 27 سال سابقه</p>
                    <div class="class-details">
                        <div class="class-detail"><i class="fas fa-calendar"></i> شنبه و دوشنبه</div>
                        <div class="class-detail"><i class="fas fa-clock"></i> 18:00 - 20:00</div>
                        <div class="class-detail"><i class="fas fa-users"></i> مناسب برای همه سنین</div>
                    </div>
                    <input type="checkbox" name="classes" value="karate" style="display: none;">
                </div>
                
                <div class="class-card" onclick="selectClass(this, 'taekwondo')">
                    <div class="class-header">
                        <div class="class-title">تکواندو</div>
                        <div class="class-price">۷۵۰,۰۰۰ تومان / ماه</div>
                    </div>
                    <p>آموزش اصولی تکواندو با تمرکز بر تکنیک‌های پا و انعطاف‌پذیری</p>
                    <div class="class-details">
                        <div class="class-detail"><i class="fas fa-calendar"></i> یکشنبه و سه‌شنبه</div>
                        <div class="class-detail"><i class="fas fa-clock"></i> 17:00 - 19:00</div>
                        <div class="class-detail"><i class="fas fa-users"></i> مناسب برای همه سنین</div>
                    </div>
                    <input type="checkbox" name="classes" value="taekwondo" style="display: none;">
                </div>
                
                <div class="class-card" onclick="selectClass(this, 'kungfu')">
                    <div class="class-header">
                        <div class="class-title">کونگ فو</div>
                        <div class="class-price">۸۵۰,۰۰۰ تومان / ماه</div>
                    </div>
                    <p>آموزش سبک‌های مختلف کونگ فو با تمرکز بر هماهنگی ذهن و بدن</p>
                    <div class="class-details">
                        <div class="class-detail"><i class="fas fa-calendar"></i> دوشنبه و چهارشنبه</div>
                        <div class="class-detail"><i class="fas fa-clock"></i> 19:00 - 21:00</div>
                        <div class="class-detail"><i class="fas fa-users"></i> مناسب برای همه سنین</div>
                    </div>
                    <input type="checkbox" name="classes" value="kungfu" style="display: none;">
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
                    <input type="checkbox" name="classes" value="fitness" style="display: none;">
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
                    <input type="checkbox" name="classes" value="kids" style="display: none;">
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
            <div class="form-section">
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
        
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
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
            const selectedClasses = document.querySelectorAll('input[name="classes"]:checked');
            if (selectedClasses.length === 0) {
                document.getElementById('classError').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('classError').style.display = 'none';
            }
            
            // If form is valid, show success message
            if (isValid) {
                document.getElementById('successMessage').style.display = 'block';
                registerForm.reset();
                
                // Scroll to success message
                document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth' });
                
                // Hide success message after 5 seconds
                setTimeout(function() {
                    document.getElementById('successMessage').style.display = 'none';
                }, 5000);
            } else {
                // Scroll to first error
                const firstError = registerForm.querySelector('.form-group.error, .error-message[style="display: block"]');
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
    });

    // Class selection
    function selectClass(element, classValue) {
        element.classList.toggle('selected');
        const checkbox = element.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        
        // Hide error message if at least one class is selected
        const selectedClasses = document.querySelectorAll('input[name="classes"]:checked');
        if (selectedClasses.length > 0) {
            document.getElementById('classError').style.display = 'none';
        }
    }
</script>
</body>
</html>