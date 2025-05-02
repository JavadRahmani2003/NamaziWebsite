<?php
namespace Modules\News\Traits;

trait Validator {
    /**
     * خطاهای اعتبارسنجی
     */
    protected $errors = [];
    
    /**
     * بررسی خالی نبودن فیلد
     * 
     * @param mixed $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateRequired($value, $fieldName) {
        if (empty($value) && $value !== '0' && $value !== 0) {
            $this->errors[$fieldName] = "فیلد $fieldName الزامی است.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی حداقل طول رشته
     * 
     * @param string $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @param int $min حداقل طول
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateMinLength($value, $fieldName, $min) {
        if (mb_strlen($value, 'UTF-8') < $min) {
            $this->errors[$fieldName] = "فیلد $fieldName باید حداقل $min کاراکتر باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی حداکثر طول رشته
     * 
     * @param string $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @param int $max حداکثر طول
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateMaxLength($value, $fieldName, $max) {
        if (mb_strlen($value, 'UTF-8') > $max) {
            $this->errors[$fieldName] = "فیلد $fieldName باید حداکثر $max کاراکتر باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی ایمیل
     * 
     * @param string $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateEmail($value, $fieldName) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$fieldName] = "فیلد $fieldName باید یک ایمیل معتبر باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی URL
     * 
     * @param string $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateUrl($value, $fieldName) {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$fieldName] = "فیلد $fieldName باید یک URL معتبر باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی عدد بودن
     * 
     * @param mixed $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateNumeric($value, $fieldName) {
        if (!is_numeric($value)) {
            $this->errors[$fieldName] = "فیلد $fieldName باید عدد باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی عدد صحیح بودن
     * 
     * @param mixed $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateInteger($value, $fieldName) {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$fieldName] = "فیلد $fieldName باید عدد صحیح باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی محدوده عددی
     * 
     * @param mixed $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @param int $min حداقل مقدار
     * @param int $max حداکثر مقدار
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateRange($value, $fieldName, $min, $max) {
        if ($value < $min || $value > $max) {
            $this->errors[$fieldName] = "فیلد $fieldName باید بین $min و $max باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی فرمت تاریخ
     * 
     * @param string $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @param string $format فرمت مورد نظر
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateDate($value, $fieldName, $format = 'Y-m-d') {
        $date = \DateTime::createFromFormat($format, $value);
        if (!$date || $date->format($format) !== $value) {
            $this->errors[$fieldName] = "فیلد $fieldName باید یک تاریخ معتبر با فرمت $format باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی مطابقت با الگو
     * 
     * @param string $value مقدار فیلد
     * @param string $fieldName نام فیلد
     * @param string $pattern الگوی مورد نظر
     * @param string $message پیام خطا
     * @return bool نتیجه اعتبارسنجی
     */
    public function validatePattern($value, $fieldName, $pattern, $message = null) {
        if (!preg_match($pattern, $value)) {
            $this->errors[$fieldName] = $message ?? "فیلد $fieldName با الگوی مورد نظر مطابقت ندارد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی نوع فایل
     * 
     * @param array $file اطلاعات فایل
     * @param string $fieldName نام فیلد
     * @param array $allowedTypes انواع مجاز
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateFileType($file, $fieldName, $allowedTypes) {
        if (empty($file['tmp_name'])) {
            return true; // فایل اختیاری است
        }
        
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension']);
        
        if (!in_array($extension, $allowedTypes)) {
            $allowedTypesStr = implode(', ', $allowedTypes);
            $this->errors[$fieldName] = "فایل $fieldName باید یکی از انواع $allowedTypesStr باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * بررسی حجم فایل
     * 
     * @param array $file اطلاعات فایل
     * @param string $fieldName نام فیلد
     * @param int $maxSize حداکثر حجم (بایت)
     * @return bool نتیجه اعتبارسنجی
     */
    public function validateFileSize($file, $fieldName, $maxSize) {
        if (empty($file['tmp_name'])) {
            return true; // فایل اختیاری است
        }
        
        if ($file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / (1024 * 1024);
            $this->errors[$fieldName] = "حجم فایل $fieldName باید کمتر از $maxSizeMB مگابایت باشد.";
            return false;
        }
        return true;
    }
    
    /**
     * پاکسازی HTML
     * 
     * @param string $value مقدار ورودی
     * @param array|string $allowedTags تگ‌های مجاز
     * @return string مقدار پاکسازی شده
     */
    public function sanitizeHtml($value, $allowedTags = null) {
        $config = require_once __DIR__ . '/../Config.php';
        $allowedTags = $allowedTags ?? $config['allowed_tags'];
        
        return strip_tags($value, $allowedTags);
    }
    
    /**
     * پاکسازی متن ساده
     * 
     * @param string $value مقدار ورودی
     * @return string مقدار پاکسازی شده
     */
    public function sanitizeString($value) {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * دریافت خطاهای اعتبارسنجی
     * 
     * @return array خطاهای اعتبارسنجی
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * بررسی وجود خطا
     * 
     * @return bool آیا خطایی وجود دارد؟
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    /**
     * پاک کردن خطاها
     */
    public function clearErrors() {
        $this->errors = [];
    }
}