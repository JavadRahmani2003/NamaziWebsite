<?php
require 'config.php';
require 'database.php';
/**
 * کلاس دریافت اخبار از پایگاه داده
 */
class NewsRecieve {
    private $conn;
    /**
     * سازنده کلاس
     */
    function __construct()
    {
        $this->conn = new Database;
    }
    
    /**
     * دریافت تمام اخبار
     */
    public function returnQueryFromDb() {
        $this->conn->getConnection();
        $sql = "SELECT * FROM news";
        $result = $this->conn->query($sql);
        return $result;
    }
    
    /**
     * دریافت خبر با شناسه مشخص
     */
    public function getNewsById($id) {
        $this->conn->getConnection();
        $sql = "SELECT * FROM news WHERE pagenumb=".$id;
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * دریافت اخبار بر اساس دسته‌بندی
     */
    public function getNewsByCategory($category) {
        $stmt = $this->conn->prepare("SELECT * FROM news WHERE category = ? ORDER BY date DESC");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    /**
     * دریافت اخبار اخیر
     */
    public function getRecentNews($limit = 5) {
        $stmt = $this->conn->prepare("SELECT * FROM news ORDER BY date DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * جستجوی اخبار
     */
    public function searchNews($keyword) {
        $search = "%$keyword%";
        $stmt = $this->conn->prepare("SELECT * FROM news WHERE header LIKE ? OR content LIKE ? ORDER BY date DESC");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * دریافت تعداد اخبار در هر دسته‌بندی
     */
    public function getCategoryCounts() {
        $query = "SELECT category, COUNT(*) as count FROM news GROUP BY category";
        $result = $this->conn->query($query);
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[$row['category']] = $row['count'];
        }
        
        return $categories;
    }
}
?>