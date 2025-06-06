-- ایجاد پایگاه داده باشگاه آرین رزم
CREATE DATABASE IF NOT EXISTS `arian_razm` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_persian_ci;

USE `arian_razm`;

-- ===================================
-- جدول کلاس‌های ورزشی
-- ===================================
CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'نام کلاس',
  `description` text COMMENT 'توضیحات کلاس',
  `price` decimal(10,0) NOT NULL DEFAULT 0 COMMENT 'قیمت ماهانه (تومان)',
  `schedule` varchar(200) COMMENT 'برنامه زمانی',
  `duration` varchar(50) COMMENT 'مدت زمان هر جلسه',
  `age_group` varchar(100) COMMENT 'گروه سنی مناسب',
  `max_students` int(11) DEFAULT 20 COMMENT 'حداکثر تعداد دانشجو',
  `instructor` varchar(100) COMMENT 'نام مربی',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'وضعیت فعال/غیرفعال',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='جدول کلاس‌های ورزشی';

-- ===================================
-- جدول ثبت نام‌ها
-- ===================================
CREATE TABLE `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL COMMENT 'نام',
  `last_name` varchar(50) NOT NULL COMMENT 'نام خانوادگی',
  `national_code` varchar(10) NOT NULL COMMENT 'کد ملی',
  `birth_date` varchar(10) NOT NULL COMMENT 'تاریخ تولد',
  `gender` enum('مرد','زن') NOT NULL COMMENT 'جنسیت',
  `education` varchar(50) DEFAULT NULL COMMENT 'تحصیلات',
  `mobile` varchar(11) NOT NULL COMMENT 'شماره موبایل',
  `phone` varchar(11) DEFAULT NULL COMMENT 'شماره ثابت',
  `email` varchar(100) DEFAULT NULL COMMENT 'ایمیل',
  `emergency_contact` varchar(11) DEFAULT NULL COMMENT 'شماره تماس اضطراری',
  `address` text NOT NULL COMMENT 'آدرس',
  `classes` text NOT NULL COMMENT 'کلاس‌های انتخابی',
  `skill_level` enum('beginner','intermediate','advanced') NOT NULL COMMENT 'سطح مهارت',
  `experience` text DEFAULT NULL COMMENT 'تجربیات قبلی',
  `has_health_issue` enum('yes','no') NOT NULL COMMENT 'سابقه بیماری',
  `health_details` text DEFAULT NULL COMMENT 'جزئیات بیماری',
  `has_injury` enum('yes','no') NOT NULL COMMENT 'سابقه آسیب‌دیدگی',
  `injury_details` text DEFAULT NULL COMMENT 'جزئیات آسیب‌دیدگی',
  `referral` varchar(50) DEFAULT NULL COMMENT 'نحوه آشنایی با باشگاه',
  `goal` varchar(50) DEFAULT NULL COMMENT 'هدف از شرکت در کلاس‌ها',
  `comments` text DEFAULT NULL COMMENT 'توضیحات تکمیلی',
  `newsletter_agreement` tinyint(1) DEFAULT 0 COMMENT 'موافقت با دریافت خبرنامه',
  `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending' COMMENT 'وضعیت ثبت نام',
  `payment_status` enum('unpaid','paid','partial') DEFAULT 'unpaid' COMMENT 'وضعیت پرداخت',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'تاریخ ثبت نام',
  `approved_date` timestamp NULL DEFAULT NULL COMMENT 'تاریخ تأیید',
  `notes` text DEFAULT NULL COMMENT 'یادداشت‌های مدیر',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_national_code` (`national_code`),
  KEY `idx_mobile` (`mobile`),
  KEY `idx_status` (`status`),
  KEY `idx_registration_date` (`registration_date`),
  KEY `idx_skill_level` (`skill_level`),
  KEY `idx_gender` (`gender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='جدول ثبت نام‌ها';

-- ===================================
-- جدول ثبت نام در کلاس‌ها (رابطه many-to-many)
-- ===================================
CREATE TABLE `registration_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL COMMENT 'شناسه ثبت نام',
  `class_id` int(11) NOT NULL COMMENT 'شناسه کلاس',
  `start_date` date DEFAULT NULL COMMENT 'تاریخ شروع',
  `end_date` date DEFAULT NULL COMMENT 'تاریخ پایان',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'وضعیت فعال/غیرفعال',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_registration_class` (`registration_id`,`class_id`),
  KEY `fk_registration_classes_class` (`class_id`),
  CONSTRAINT `fk_registration_classes_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_registration_classes_registration` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='جدول ثبت نام در کلاس‌ها';

-- ===================================
-- جدول مربیان
-- ===================================
CREATE TABLE `instructors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL COMMENT 'نام',
  `last_name` varchar(50) NOT NULL COMMENT 'نام خانوادگی',
  `mobile` varchar(11) NOT NULL COMMENT 'شماره موبایل',
  `email` varchar(100) DEFAULT NULL COMMENT 'ایمیل',
  `specialization` varchar(200) COMMENT 'تخصص',
  `experience_years` int(11) DEFAULT 0 COMMENT 'سال‌های تجربه',
  `bio` text COMMENT 'بیوگرافی',
  `photo` varchar(255) DEFAULT NULL COMMENT 'عکس مربی',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'وضعیت فعال/غیرفعال',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='جدول مربیان';

-- ===================================
-- جدول پرداخت‌ها
-- ===================================
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL COMMENT 'شناسه ثبت نام',
  `amount` decimal(10,0) NOT NULL COMMENT 'مبلغ (تومان)',
  `payment_method` enum('cash','card','online','bank_transfer') NOT NULL COMMENT 'روش پرداخت',
  `transaction_id` varchar(100) DEFAULT NULL COMMENT 'شناسه تراکنش',
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending' COMMENT 'وضعیت پرداخت',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'تاریخ پرداخت',
  `description` text DEFAULT NULL COMMENT 'توضیحات',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_payments_registration` (`registration_id`),
  KEY `idx_status` (`status`),
  KEY `idx_payment_date` (`payment_date`),
  CONSTRAINT `fk_payments_registration` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='جدول پرداخت‌ها';

-- ===================================
-- جدول حضور و غیاب
-- ===================================
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_id` int(11) NOT NULL COMMENT 'شناسه ثبت نام',
  `class_id` int(11) NOT NULL COMMENT 'شناسه کلاس',
  `attendance_date` date NOT NULL COMMENT 'تاریخ حضور',
  `status` enum('present','absent','late','excused') NOT NULL COMMENT 'وضعیت حضور',
  `notes` text DEFAULT NULL COMMENT 'یادداشت‌ها',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_attendance_unique` (`registration_id`,`class_id`,`attendance_date`),
  KEY `fk_attendance_class` (`class_id`),
  KEY `idx_attendance_date` (`attendance_date`),
  CONSTRAINT `fk_attendance_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_attendance_registration` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='جدول حضور و غیاب';

-- ===================================
-- جدول مدیران سیستم
-- ===================================
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT 'نام کاربری',
  `password` varchar(255) NOT NULL COMMENT 'رمز عبور (هش شده)',
  `first_name` varchar(50) NOT NULL COMMENT 'نام',
  `last_name` varchar(50) NOT NULL COMMENT 'نام خانوادگی',
  `email` varchar(100) NOT NULL COMMENT 'ایمیل',
  `role` enum('super_admin','admin','manager') DEFAULT 'admin' COMMENT 'نقش کاربری',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'وضعیت فعال/غیرفعال',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'آخرین ورود',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`),
  UNIQUE KEY `idx_email` (`email`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='جدول مدیران سیستم';

-- ===================================
-- جدول تنظیمات سیستم
-- ===================================
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL COMMENT 'کلید تنظیم',
  `setting_value` text COMMENT 'مقدار تنظیم',
  `description` varchar(255) DEFAULT NULL COMMENT 'توضیحات',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci COMMENT='جدول تنظیمات سیستم';

-- ===================================
-- درج داده‌های اولیه
-- ===================================

-- درج کلاس‌های پیش‌فرض
INSERT INTO `classes` (`name`, `description`, `price`, `schedule`, `duration`, `age_group`, `max_students`, `is_active`) VALUES
('دفاع شخصی', 'برنامه‌های ویژه دفاع شخصی برای ارتقای توان بدنی و استقامت', 650000, 'شنبه تا پنج‌شنبه', '21:00 - 22:00', 'مناسب برای همه سنین', 15, 1),
('آمادگی جسمانی', 'برنامه‌های ویژه آمادگی جسمانی برای ارتقای توان بدنی و استقامت', 650000, 'شنبه تا پنج‌شنبه', '20:00 - 21:30', 'مناسب برای همه سنین', 20, 1),
('کلاس کودکان', 'آموزش هنرهای رزمی به کودکان با روش‌های سرگرم‌کننده و آموزنده', 700000, 'یکشنبه و سه‌شنبه', '16:00 - 17:30', 'مناسب برای 5 تا 12 سال', 12, 1);

-- درج مربی نمونه
INSERT INTO `instructors` (`first_name`, `last_name`, `mobile`, `specialization`, `experience_years`, `bio`, `is_active`) VALUES
('احمد', 'محمدی', '09123456789', 'تکواندو، دفاع شخصی', 8, 'مربی مجرب تکواندو با سابقه قهرمانی در مسابقات ملی', 1),
('فاطمه', 'احمدی', '09123456788', 'آمادگی جسمانی، یوگا', 5, 'مربی آمادگی جسمانی و یوگا با مدرک بین‌المللی', 1);

-- درج مدیر پیش‌فرض (رمز عبور: admin123)
INSERT INTO `admins` (`username`, `password`, `first_name`, `last_name`, `email`, `role`, `is_active`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'مدیر', 'سیستم', 'admin@arianrazm.com', 'super_admin', 1);

-- درج تنظیمات پیش‌فرض
INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('site_name', 'باشگاه ورزشی آرین رزم', 'نام سایت'),
('site_email', 'info@arianrazm.com', 'ایمیل سایت'),
('site_phone', '021-12345678', 'شماره تلفن سایت'),
('site_address', 'تهران، خیابان ولیعصر، پلاک 123', 'آدرس باشگاه'),
('registration_fee', '50000', 'هزینه ثبت نام (تومان)'),
('max_registrations_per_day', '50', 'حداکثر تعداد ثبت نام در روز'),
('auto_approve_registrations', '0', 'تأیید خودکار ثبت نام‌ها (0=خیر، 1=بله)');

-- ===================================
-- ایجاد ویوهای مفید
-- ===================================

-- ویو برای نمایش اطلاعات کامل ثبت نام‌ها
CREATE VIEW `v_registrations_full` AS
SELECT 
    r.id,
    r.first_name,
    r.last_name,
    CONCAT(r.first_name, ' ', r.last_name) AS full_name,
    r.national_code,
    r.birth_date,
    r.gender,
    r.mobile,
    r.email,
    r.classes,
    r.skill_level,
    r.status,
    r.payment_status,
    r.registration_date,
    r.approved_date,
    COALESCE(SUM(p.amount), 0) AS total_paid,
    COUNT(DISTINCT rc.class_id) AS enrolled_classes_count
FROM registrations r
LEFT JOIN payments p ON r.id = p.registration_id AND p.status = 'completed'
LEFT JOIN registration_classes rc ON r.id = rc.registration_id AND rc.is_active = 1
GROUP BY r.id;

-- ویو برای آمار کلی
CREATE VIEW `v_dashboard_stats` AS
SELECT 
    (SELECT COUNT(*) FROM registrations WHERE status = 'pending') AS pending_registrations,
    (SELECT COUNT(*) FROM registrations WHERE status = 'approved') AS approved_registrations,
    (SELECT COUNT(*) FROM registrations WHERE DATE(registration_date) = CURDATE()) AS today_registrations,
    (SELECT COUNT(*) FROM registrations WHERE DATE(registration_date) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) AS week_registrations,
    (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'completed' AND DATE(payment_date) = CURDATE()) AS today_income,
    (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'completed' AND MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE())) AS month_income;

-- ===================================
-- ایجاد پروسیجرهای مفید
-- ===================================

DELIMITER //

-- پروسیجر برای تأیید ثبت نام
CREATE PROCEDURE `sp_approve_registration`(
    IN p_registration_id INT,
    IN p_admin_notes TEXT
)
BEGIN
    UPDATE registrations 
    SET 
        status = 'approved',
        approved_date = NOW(),
        notes = p_admin_notes,
        updated_at = NOW()
    WHERE id = p_registration_id;
END //

-- پروسیجر برای محاسبه آمار ماهانه
CREATE PROCEDURE `sp_monthly_stats`(
    IN p_year INT,
    IN p_month INT
)
BEGIN
    SELECT 
        COUNT(*) AS total_registrations,
        COUNT(CASE WHEN status = 'approved' THEN 1 END) AS approved_registrations,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) AS pending_registrations,
        COUNT(CASE WHEN status = 'rejected' THEN 1 END) AS rejected_registrations,
        COUNT(CASE WHEN gender = 'مرد' THEN 1 END) AS male_registrations,
        COUNT(CASE WHEN gender = 'زن' THEN 1 END) AS female_registrations,
        COALESCE(SUM(CASE WHEN p.status = 'completed' THEN p.amount ELSE 0 END), 0) AS total_income
    FROM registrations r
    LEFT JOIN payments p ON r.id = p.registration_id
    WHERE YEAR(r.registration_date) = p_year 
    AND MONTH(r.registration_date) = p_month;
END //

DELIMITER ;

-- ===================================
-- ایجاد ایندکس‌های بهینه‌سازی
-- ===================================

-- ایندکس‌های ترکیبی برای جستجوهای پرکاربرد
CREATE INDEX `idx_registrations_status_date` ON `registrations` (`status`, `registration_date`);
CREATE INDEX `idx_registrations_gender_skill` ON `registrations` (`gender`, `skill_level`);
CREATE INDEX `idx_payments_status_date` ON `payments` (`status`, `payment_date`);

-- ===================================
-- تنظیمات نهایی
-- ===================================

-- فعال‌سازی رویدادها
SET GLOBAL event_scheduler = ON;

-- ایجاد رویداد برای پاک‌سازی خودکار داده‌های قدیمی (اختیاری)
CREATE EVENT IF NOT EXISTS `ev_cleanup_old_data`
ON SCHEDULE EVERY 1 MONTH
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- حذف ثبت نام‌های رد شده بیش از 6 ماه
    DELETE FROM registrations 
    WHERE status = 'rejected' 
    AND registration_date < DATE_SUB(NOW(), INTERVAL 6 MONTH);
    
    -- حذف پرداخت‌های ناموفق بیش از 3 ماه
    DELETE FROM payments 
    WHERE status = 'failed' 
    AND created_at < DATE_SUB(NOW(), INTERVAL 3 MONTH);
END;

-- پایان اسکریپت
SELECT 'پایگاه داده باشگاه آرین رزم با موفقیت ایجاد شد!' AS message;