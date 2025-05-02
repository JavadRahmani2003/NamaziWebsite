<?php
namespace Modules\News;

use Modules\News\Interfaces\RepositoryInterface;
use Modules\News\Exceptions\NewsException;

class NewsRepository implements RepositoryInterface {
    /**
     * اتصال به پایگاه داده
     */
    private $db;
    
    /**
     * نام جدول
     */
    private $table = 'news';
    
    /**
     * سازنده کلاس
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * دریافت همه اخبار
     * 
     * @param array $options گزینه‌های اضافی
     * @return array لیست اخبار
     */
    public function getAll(array $options = []) {
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        $orderBy = $options['order_by'] ?? 'publish_date';
        $orderDir = $options['order_dir'] ?? 'DESC';
        $category = $options['category'] ?? null;
        $status = $options['status'] ?? 'published';
        $search = $options['search'] ?? null;
        
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        
        if ($category) {
            $sql .= " AND category_id = " . intval($category);
        }
        
        if ($status) {
            $sql .= " AND status = '" . $this->db->escapeString($status) . "'";
        }
        
        if ($search) {
            $search = $this->db->escapeString($search);
            $sql .= " AND (title LIKE '%{$search}%' OR content LIKE '%{$search}%')";
        }
        
        $sql .= " ORDER BY {$orderBy} {$orderDir} LIMIT {$offset}, {$limit}";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در دریافت اخبار: " . $this->db->getConnection()->error);
        }
        
        $news = [];
        while ($row = $result->fetch_assoc()) {
            $news[] = new News($row);
        }
        
        return $news;
    }
    
    /**
     * دریافت تعداد کل اخبار
     * 
     * @param array $options گزینه‌های اضافی
     * @return int تعداد کل
     */
    public function count(array $options = []) {
        $category = $options['category'] ?? null;
        $status = $options['status'] ?? 'published';
        $search = $options['search'] ?? null;
        
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        
        if ($category) {
            $sql .= " AND category_id = " . intval($category);
        }
        
        if ($status) {
            $sql .= " AND status = '" . $this->db->escapeString($status) . "'";
        }
        
        if ($search) {
            $search = $this->db->escapeString($search);
            $sql .= " AND (title LIKE '%{$search}%' OR content LIKE '%{$search}%')";
        }
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در شمارش اخبار: " . $this->db->getConnection()->error);
        }
        
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }
    
    /**
     * دریافت خبر با شناسه
     * 
     * @param int $id شناسه خبر
     * @return News خبر یافت شده
     * @throws NewsException اگر خبر یافت نشود
     */
    public function getById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id} OR pagenumb = {$id} LIMIT 1";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در دریافت خبر: " . $this->db->getConnection()->error);
        }
        
        if ($result->num_rows === 0) {
            throw NewsException::notFound("خبر با شناسه {$id} یافت نشد.");
        }
        
        $data = $result->fetch_assoc();
        return new News($data);
    }
    
    /**
     * دریافت اخبار اخیر
     * 
     * @param int $limit تعداد
     * @return array لیست اخبار
     */
    public function getRecent($limit = 5) {
        return $this->getAll([
            'limit' => $limit,
            'order_by' => 'publish_date',
            'order_dir' => 'DESC',
            'status' => 'published'
        ]);
    }
    
    /**
     * دریافت اخبار پربازدید
     * 
     * @param int $limit تعداد
     * @return array لیست اخبار
     */
    public function getPopular($limit = 5) {
        return $this->getAll([
            'limit' => $limit,
            'order_by' => 'view_count',
            'order_dir' => 'DESC',
            'status' => 'published'
        ]);
    }
    
    /**
     * دریافت اخبار مرتبط
     * 
     * @param int $newsId شناسه خبر
     * @param int $limit تعداد
     * @return array لیست اخبار
     */
    public function getRelated($newsId, $limit = 5) {
        try {
            $news = $this->getById($newsId);
            
            $sql = "SELECT * FROM {$this->table} 
                    WHERE id != {$newsId} 
                    AND category_id = {$news->getCategoryId()} 
                    AND status = 'published' 
                    ORDER BY publish_date DESC 
                    LIMIT {$limit}";
            
            $result = $this->db->query($sql);
            
            if (!$result) {
                throw NewsException::database("خطا در دریافت اخبار مرتبط: " . $this->db->getConnection()->error);
            }
            
            $relatedNews = [];
            while ($row = $result->fetch_assoc()) {
                $relatedNews[] = new News($row);
            }
            
            return $relatedNews;
        } catch (NewsException $e) {
            if ($e->getCode() === NewsException::ERROR_NOT_FOUND) {
                return [];
            }
            throw $e;
        }
    }
    
    /**
     * جستجوی اخبار
     * 
     * @param string $keyword کلمه کلیدی
     * @param array $options گزینه‌های اضافی
     * @return array لیست اخبار
     */
    public function search($keyword, array $options = []) {
        $options['search'] = $keyword;
        return $this->getAll($options);
    }
    
    /**
     * ایجاد خبر جدید
     * 
     * @param array $data داده‌های خبر
     * @return int شناسه خبر ایجاد شده
     * @throws NewsException در صورت بروز خطا
     */
    public function create(array $data) {
        $news = new News($data);
        $news->validate();
        
        $data = $news->toArray();
        unset($data['id']);
        
        $fields = [];
        $values = [];
        
        foreach ($data as $field => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            
            if ($value !== null) {
                $fields[] = $field;
                $values[] = "'" . $this->db->escapeString($value) . "'";
            }
        }
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در ایجاد خبر: " . $this->db->getConnection()->error);
        }
        
        return $this->db->getLastInsertId();
    }
    
    /**
     * به‌روزرسانی خبر
     * 
     * @param int $id شناسه خبر
     * @param array $data داده‌های جدید
     * @return bool نتیجه عملیات
     * @throws NewsException در صورت بروز خطا
     */
    public function update($id, array $data) {
        $id = intval($id);
        
        // دریافت خبر موجود
        $existingNews = $this->getById($id);
        
        // ترکیب داده‌های موجود با داده‌های جدید
        $existingNews->fill($data);
        $existingNews->validate();
        
        $data = $existingNews->toArray();
        unset($data['id']);
        
        $updates = [];
        
        foreach ($data as $field => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            
            if ($value !== null) {
                $updates[] = "{$field} = '" . $this->db->escapeString($value) . "'";
            }
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE id = {$id}";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در به‌روزرسانی خبر: " . $this->db->getConnection()->error);
        }
        
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * حذف خبر
     * 
     * @param int $id شناسه خبر
     * @return bool نتیجه عملیات
     * @throws NewsException در صورت بروز خطا
     */
    public function delete($id) {
        $id = intval($id);
        
        // بررسی وجود خبر
        $this->getById($id);
        
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در حذف خبر: " . $this->db->getConnection()->error);
        }
        
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * افزایش تعداد بازدید
     * 
     * @param int $id شناسه خبر
     * @param int $count تعداد افزایش
     * @return bool نتیجه عملیات
     */
    public function incrementViewCount($id, $count = 1) {
        $id = intval($id);
        $count = intval($count);
        
        $sql = "UPDATE {$this->table} SET view_count = view_count + {$count} WHERE id = {$id}";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در افزایش تعداد بازدید: " . $this->db->getConnection()->error);
        }
        
        return $this->db->getAffectedRows() > 0;
    }
    
    /**
     * دریافت اخبار بر اساس دسته‌بندی
     * 
     * @param int $categoryId شناسه دسته‌بندی
     * @param array $options گزینه‌های اضافی
     * @return array لیست اخبار
     */
    public function getByCategory($categoryId, array $options = []) {
        $options['category'] = $categoryId;
        return $this->getAll($options);
    }
    
    /**
     * دریافت آرشیو اخبار بر اساس سال و ماه
     * 
     * @param int $year سال
     * @param int $month ماه
     * @param array $options گزینه‌های اضافی
     * @return array لیست اخبار
     */
    public function getArchive($year, $month = null, array $options = []) {
        $sql = "SELECT * FROM {$this->table} WHERE YEAR(publish_date) = " . intval($year);
        
        if ($month !== null) {
            $sql .= " AND MONTH(publish_date) = " . intval($month);
        }
        
        $sql .= " AND status = 'published' ORDER BY publish_date DESC";
        
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        
        $sql .= " LIMIT {$offset}, {$limit}";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در دریافت آرشیو اخبار: " . $this->db->getConnection()->error);
        }
        
        $news = [];
        while ($row = $result->fetch_assoc()) {
            $news[] = new News($row);
        }
        
        return $news;
    }
    
    /**
     * دریافت آمار ماهانه اخبار
     * 
     * @param int $year سال
     * @return array آمار ماهانه
     */
    public function getMonthlyStats($year = null) {
        $year = $year ?? date('Y');
        
        $sql = "SELECT MONTH(publish_date) as month, COUNT(*) as count 
                FROM {$this->table} 
                WHERE YEAR(publish_date) = " . intval($year) . " 
                AND status = 'published' 
                GROUP BY MONTH(publish_date) 
                ORDER BY month";
        
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw NewsException::database("خطا در دریافت آمار ماهانه: " . $this->db->getConnection()->error);
        }
        
        $stats = [];
        while ($row = $result->fetch_assoc()) {
            $stats[$row['month']] = $row['count'];
        }
        
        return $stats;
    }
}