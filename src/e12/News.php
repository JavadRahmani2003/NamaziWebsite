<?php
namespace Modules\News;

use Modules\News\Traits\DateFormatter;
use Modules\News\Traits\Validator;
use Modules\News\Exceptions\NewsException;

class News {
    use DateFormatter, Validator;
    
    /**
     * شناسه خبر
     */
    private $id;
    
    /**
     * عنوان خبر
     */
    private $title;
    
    /**
     * محتوای خبر
     */
    private $content;
    
    /**
     * خلاصه خبر
     */
    private $excerpt;
    
    /**
     * تاریخ انتشار
     */
    private $publishDate;
    
    /**
     * وضعیت انتشار
     */
    private $status;
    
    /**
     * شناسه نویسنده
     */
    private $authorId;
    
    /**
     * شناسه دسته‌بندی
     */
    private $categoryId;
    
    /**
     * تصویر شاخص
     */
    private $featuredImage;
    
    /**
     * تعداد بازدید
     */
    private $viewCount;
    
    /**
     * برچسب‌ها
     */
    private $tags;
    
    /**
     * تصاویر گالری
     */
    private $gallery;
    
    /**
     * نظرات
     */
    private $comments;
    
    /**
     * سازنده کلاس
     * 
     * @param array $data داده‌های خبر
     */
    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->fill($data);
        }
    }
    
    /**
     * پر کردن ویژگی‌ها از آرایه
     * 
     * @param array $data داده‌ها
     * @return self
     */
    public function fill(array $data) {
        $this->id = $data['id'] ?? $this->id ?? null;
        $this->title = $data['title'] ?? $data['header'] ?? $this->title ?? '';
        $this->content = $data['content'] ?? $data['thebody'] ?? $this->content ?? '';
        $this->excerpt = $data['excerpt'] ?? $this->excerpt ?? '';
        $this->publishDate = $data['publish_date'] ?? $data['date'] ?? $this->publishDate ?? date('Y-m-d H:i:s');
        $this->status = $data['status'] ?? $this->status ?? 'published';
        $this->authorId = $data['author_id'] ?? $this->authorId ?? 1;
        $this->categoryId = $data['category_id'] ?? $this->categoryId ?? 1;
        $this->featuredImage = $data['featured_image'] ?? $data['image_path'] ?? $this->featuredImage ?? '';
        $this->viewCount = $data['view_count'] ?? $this->viewCount ?? 0;
        $this->tags = $data['tags'] ?? $this->tags ?? [];
        $this->gallery = $data['gallery'] ?? $this->gallery ?? [];
        $this->comments = $data['comments'] ?? $this->comments ?? [];
        
        return $this;
    }
    
    /**
     * اعتبارسنجی داده‌ها
     * 
     * @return bool نتیجه اعتبارسنجی
     * @throws NewsException در صورت بروز خطا
     */
    public function validate() {
        $this->clearErrors();
        
        $this->validateRequired($this->title, 'title');
        $this->validateMinLength($this->title, 'title', 3);
        $this->validateMaxLength($this->title, 'title', 255);
        
        $this->validateRequired($this->content, 'content');
        $this->validateMinLength($this->content, 'content', 10);
        
        if ($this->hasErrors()) {
            throw NewsException::validation("داده‌های خبر نامعتبر هستند.", $this->getErrors());
        }
        
        return true;
    }
    
    /**
     * تبدیل به آرایه
     * 
     * @return array داده‌های خبر
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'publish_date' => $this->publishDate,
            'status' => $this->status,
            'author_id' => $this->authorId,
            'category_id' => $this->categoryId,
            'featured_image' => $this->featuredImage,
            'view_count' => $this->viewCount,
            'tags' => $this->tags,
            'gallery' => $this->gallery,
            'comments' => $this->comments,
        ];
    }
    
    /**
     * ایجاد خلاصه از محتوا
     * 
     * @param int $length طول خلاصه
     * @return string خلاصه
     */
    public function generateExcerpt($length = null) {
        $config = require_once __DIR__ . '/Config.php';
        $length = $length ?? $config['news_excerpt_length'];
        
        $content = strip_tags($this->content);
        if (mb_strlen($content, 'UTF-8') <= $length) {
            return $content;
        }
        
        $excerpt = mb_substr($content, 0, $length, 'UTF-8');
        $lastSpace = mb_strrpos($excerpt, ' ', 0, 'UTF-8');
        
        if ($lastSpace !== false) {
            $excerpt = mb_substr($excerpt, 0, $lastSpace, 'UTF-8');
        }
        
        return $excerpt . '...';
    }
    
    /**
     * افزایش تعداد بازدید
     * 
     * @param int $count تعداد افزایش
     * @return self
     */
    public function incrementViewCount($count = 1) {
        $this->viewCount += $count;
        return $this;
    }
    
    /**
     * افزودن نظر
     * 
     * @param array $comment داده‌های نظر
     * @return self
     */
    public function addComment(array $comment) {
        $this->comments[] = $comment;
        return $this;
    }
    
    /**
     * افزودن تصویر به گالری
     * 
     * @param string $image مسیر تصویر
     * @return self
     */
    public function addGalleryImage($image) {
        $this->gallery[] = $image;
        return $this;
    }
    
    /**
     * افزودن برچسب
     * 
     * @param string $tag برچسب
     * @return self
     */
    public function addTag($tag) {
        if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }
        return $this;
    }
    
    /**
     * حذف برچسب
     * 
     * @param string $tag برچسب
     * @return self
     */
    public function removeTag($tag) {
        $key = array_search($tag, $this->tags);
        if ($key !== false) {
            unset($this->tags[$key]);
            $this->tags = array_values($this->tags);
        }
        return $this;
    }
    
    // Getters و Setters
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    public function getExcerpt() {
        if (empty($this->excerpt)) {
            $this->excerpt = $this->generateExcerpt();
        }
        return $this->excerpt;
    }
    
    public function setExcerpt($excerpt) {
        $this->excerpt = $excerpt;
        return $this;
    }
    
    public function getPublishDate() {
        return $this->publishDate;
    }
    
    public function getFormattedPublishDate($format = null, $convertToJalali = true) {
        return $this->formatDate($this->publishDate, $format, $convertToJalali);
    }
    
    public function setPublishDate($publishDate) {
        $this->publishDate = $publishDate;
        return $this;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
    
    public function getAuthorId() {
        return $this->authorId;
    }
    
    public function setAuthorId($authorId) {
        $this->authorId = $authorId;
        return $this;
    }
    
    public function getCategoryId() {
        return $this->categoryId;
    }
    
    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
        return $this;
    }
    
    public function getFeaturedImage() {
        return $this->featuredImage;
    }
    
    public function setFeaturedImage($featuredImage) {
        $this->featuredImage = $featuredImage;
        return $this;
    }
    
    public function getViewCount() {
        return $this->viewCount;
    }
    
    public function setViewCount($viewCount) {
        $this->viewCount = $viewCount;
        return $this;
    }
    
    public function getTags() {
        return $this->tags;
    }
    
    public function setTags($tags) {
        $this->tags = $tags;
        return $this;
    }
    
    public function getGallery() {
        return $this->gallery;
    }
    
    public function setGallery($gallery) {
        $this->gallery = $gallery;
        return $this;
    }
    
    public function getComments() {
        return $this->comments;
    }
    
    public function setComments($comments) {
        $this->comments = $comments;
        return $this;
    }
    
    /**
     * بررسی انتشار خبر
     * 
     * @return bool آیا خبر منتشر شده است؟
     */
    public function isPublished() {
        return $this->status === 'published' && strtotime($this->publishDate) <= time();
    }
}