<?php
namespace Modules\News\Interfaces;

interface RepositoryInterface {
    /**
     * دریافت همه موارد
     * 
     * @param array $options گزینه‌های اضافی
     * @return array لیست موارد
     */
    public function getAll(array $options = []);
    
    /**
     * دریافت یک مورد با شناسه
     * 
     * @param int $id شناسه
     * @return mixed مورد یافت شده یا null
     */
    public function getById($id);
    
    /**
     * ایجاد یک مورد جدید
     * 
     * @param array $data داده‌های مورد
     * @return mixed شناسه مورد ایجاد شده یا false
     */
    public function create(array $data);
    
    /**
     * به‌روزرسانی یک مورد
     * 
     * @param int $id شناسه
     * @param array $data داده‌های جدید
     * @return bool نتیجه عملیات
     */
    public function update($id, array $data);
    
    /**
     * حذف یک مورد
     * 
     * @param int $id شناسه
     * @return bool نتیجه عملیات
     */
    public function delete($id);
}