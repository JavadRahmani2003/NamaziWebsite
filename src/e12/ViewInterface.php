<?php
namespace Modules\News\Interfaces;

interface ViewInterface {
    /**
     * نمایش لیست موارد
     * 
     * @param array $items لیست موارد
     * @param array $options گزینه‌های اضافی
     * @return string خروجی HTML
     */
    public function renderList(array $items, array $options = []);
    
    /**
     * نمایش جزئیات یک مورد
     * 
     * @param mixed $item مورد
     * @param array $options گزینه‌های اضافی
     * @return string خروجی HTML
     */
    public function renderDetail($item, array $options = []);
    
    /**
     * نمایش فرم
     * 
     * @param mixed $item مورد (برای ویرایش)
     * @param array $options گزینه‌های اضافی
     * @return string خروجی HTML
     */
    public function renderForm($item = null, array $options = []);
    
    /**
     * نمایش پیام
     * 
     * @param string $message متن پیام
     * @param string $type نوع پیام
     * @return string خروجی HTML
     */
    public function renderMessage($message, $type = 'info');
}