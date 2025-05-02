<?php
namespace Modules\News\Exceptions;

class NewsException extends \Exception {
    /**
     * کدهای خطا
     */
    const ERROR_NOT_FOUND = 404;
    const ERROR_VALIDATION = 422;
    const ERROR_DATABASE = 500;
    const ERROR_PERMISSION = 403;
    const ERROR_FILE_UPLOAD = 415;
    
    /**
     * داده‌های اضافی خطا
     */
    protected $data;
    
    /**
     * سازنده کلاس
     * 
     * @param string $message پیام خطا
     * @param int $code کد خطا
     * @param array $data داده‌های اضافی
     * @param \Throwable $previous خطای قبلی
     */
    public function __construct($message = "", $code = 0, $data = [], \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }
    
    /**
     * دریافت داده‌های اضافی
     * 
     * @return array داده‌های اضافی
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * ایجاد خطای "یافت نشد"
     * 
     * @param string $message پیام خطا
     * @param array $data داده‌های اضافی
     * @return self نمونه خطا
     */
    public static function notFound($message = "مورد درخواستی یافت نشد.", $data = []) {
        return new self($message, self::ERROR_NOT_FOUND, $data);
    }
    
    /**
     * ایجاد خطای اعتبارسنجی
     * 
     * @param string $message پیام خطا
     * @param array $data داده‌های اضافی
     * @return self نمونه خطا
     */
    public static function validation($message = "داده‌های ورودی نامعتبر هستند.", $data = []) {
        return new self($message, self::ERROR_VALIDATION, $data);
    }
    
    /**
     * ایجاد خطای پایگاه داده
     * 
     * @param string $message پیام خطا
     * @param array $data داده‌های اضافی
     * @return self نمونه خطا
     */
    public static function database($message = "خطا در عملیات پایگاه داده.", $data = []) {
        return new self($message, self::ERROR_DATABASE, $data);
    }
    
    /**
     * ایجاد خطای دسترسی
     * 
     * @param string $message پیام خطا
     * @param array $data داده‌های اضافی
     * @return self نمونه خطا
     */
    public static function permission($message = "شما دسترسی لازم برای این عملیات را ندارید.", $data = []) {
        return new self($message, self::ERROR_PERMISSION, $data);
    }
    
    /**
     * ایجاد خطای آپلود فایل
     * 
     * @param string $message پیام خطا
     * @param array $data داده‌های اضافی
     * @return self نمونه خطا
     */
    public static function fileUpload($message = "خطا در آپلود فایل.", $data = []) {
        return new self($message, self::ERROR_FILE_UPLOAD, $data);
    }
}