<?php
namespace Modules\News;

use Modules\News\Interfaces\ViewInterface;
use Modules\News\Traits\DateFormatter;

class NewsView implements ViewInterface {
    use DateFormatter;
    
    /**
     * تنظیمات
     */
    private $config;
    
    /**
     * سازنده کلاس
     */
    public function __construct() {
        $this->config = require_once __DIR__ . '/Config.php';
    }
    
    /**
     * نمایش لیست اخبار
     * 
     * @param array $items لیست اخبار
     * @param array $options گزینه‌های اضافی
     * @return string خروجی HTML
     */
    public function renderList(array $items, array $options = []) {
        $output = '<div class="news-container">';
        
        // هدر  array $options = []) {
        $output = '<div class="news-container">';
        
        // هدر
        $output .= '<div class="news-header">';
        $output .= '<h1 class="news-title">' . ($options['title'] ?? 'اخبار و رویدادها') . '</h1>';
        $output .= '<p class="news-subtitle">' . ($options['subtitle'] ?? 'آخرین اخبار و رویدادهای باشگاه آرین رزم') . '</p>';
        $output .= '</div>';
        
        // محتوای اصلی
        $output .= '<div class="news-content">';
        $output .= '<div class="news-grid">';
        
        // بخش اصلی اخبار
        $output .= '<div class="news-main">';
        
        if (empty($items)) {
            $output .= '<div class="empty-message">در حال حاضر خبری برای نمایش وجود ندارد.</div>';
        } else {
            foreach ($items as $item) {
                $output .= $this->renderNewsCard($item);
            }
        }
        
        // صفحه‌بندی
        if (!empty($options['pagination'])) {
            $output .= $this->renderPagination($options['pagination']);
        }
        
        $output .= '</div>';
        
        // سایدبار
        if (!empty($options['sidebar'])) {
            $output .= '<div class="news-sidebar">';
            $output .= $options['sidebar'];
            $output .= '</div>';
        }
        
        $output .= '</div>'; // پایان news-grid
        $output .= '</div>'; // پایان news-content
        $output .= '</div>'; // پایان news-container
        
        return $output;
    }
    
    /**
     * نمایش کارت خبر
     * 
     * @param News $news خبر
     * @return string خروجی HTML
     */
    private function renderNewsCard($news) {
        $output = '<div class="news-card">';
        
        // تصویر خبر
        $imagePath = $news->getFeaturedImage() ?: '/placeholder.svg?height=200&width=300';
        $output .= '<div class="news-card-image">';
        $output .= '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($news->getTitle()) . '">';
        $output .= '</div>';
        
        // محتوای کارت
        $output .= '<div class="news-card-content">';
        
        // تاریخ
        $output .= '<div class="news-card-date">';
        $output .= '<i class="far fa-calendar-alt"></i> ';
        $output .= $news->getFormattedPublishDate();
        $output .= '</div>';
        
        // عنوان
        $output .= '<h3 class="news-card-title">' . htmlspecialchars($news->getTitle()) . '</h3>';
        
        // خلاصه
        $output .= '<p class="news-card-excerpt">' . htmlspecialchars($news->getExcerpt()) . '</p>';
        
        // لینک ادامه مطلب
        $output .= '<a href="news-detail.php?id=' . $news->getId() . '" class="news-card-link">ادامه مطلب</a>';
        
        $output .= '</div>'; // پایان news-card-content
        $output .= '</div>'; // پایان news-card
        
        return $output;
    }
    
    /**
     * نمایش صفحه‌بندی
     * 
     * @param array $pagination اطلاعات صفحه‌بندی
     * @return string خروجی HTML
     */
    private function renderPagination($pagination) {
        $currentPage = $pagination['current_page'] ?? 1;
        $totalPages = $pagination['total_pages'] ?? 1;
        $baseUrl = $pagination['base_url'] ?? '?page=';
        
        $output = '<div class="news-pagination">';
        $output .= '<ul class="pagination">';
        
        // دکمه قبلی
        $prevDisabled = ($currentPage <= 1) ? ' disabled' : '';
        $output .= '<li class="page-item' . $prevDisabled . '">';
        $output .= '<a class="page-link" href="' . $baseUrl . ($currentPage - 1) . '"><i class="fas fa-chevron-right"></i></a>';
        $output .= '</li>';
        
        // صفحات
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);
        
        for ($i = $startPage; $i <= $endPage; $i++) {
            $active = ($i == $currentPage) ? ' active' : '';
            $output .= '<li class="page-item' . $active . '">';
            $output .= '<a class="page-link" href="' . $baseUrl . $i . '">' . $i . '</a>';
            $output .= '</li>';
        }
        
        // دکمه بعدی
        $nextDisabled = ($currentPage >= $totalPages) ? ' disabled' : '';
        $output .= '<li class="page-item' . $nextDisabled . '">';
        $output .= '<a class="page-link" href="' . $baseUrl . ($currentPage + 1) . '"><i class="fas fa-chevron-left"></i></a>';
        $output .= '</li>';
        
        $output .= '</ul>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * نمایش جزئیات خبر
     * 
     * @param News $item خبر
     * @param array $options گزینه‌های اضافی
     * @return string خروجی HTML
     */
    public function renderDetail($item, array $options = []) {
        $output = '<div class="news-detail-container">';
        $output .= '<div class="news-detail-content">';
        $output .= '<div class="news-detail-grid">';
        
        // محتوای اصلی خبر
        $output .= '<div class="news-detail-main">';
        
        // تصویر خبر
        $imagePath = $item->getFeaturedImage() ?: '/placeholder.svg?height=400&width=800';
        $output .= '<div class="news-detail-image">';
        $output .= '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($item->getTitle()) . '">';
        $output .= '</div>';
        
        // هدر خبر
        $output .= '<div class="news-detail-header">';
        
        // متادیتا
        $output .= '<div class="news-detail-meta">';
        $output .= '<div class="news-detail-meta-item">';
        $output .= '<i class="far fa-calendar-alt"></i> ' . $item->getFormattedPublishDate();
        $output .= '</div>';
        
        $output .= '<div class="news-detail-meta-item">';
        $output .= '<i class="far fa-user"></i> ' . ($options['author_name'] ?? 'مدیر سایت');
        $output .= '</div>';
        
        $output .= '<div class="news-detail-meta-item">';
        $output .= '<i class="far fa-folder"></i> ' . ($options['category_name'] ?? 'اخبار باشگاه');
        $output .= '</div>';
        
        $output .= '<div class="news-detail-meta-item">';
        $output .= '<i class="far fa-eye"></i> ' . $item->getViewCount() . ' بازدید';
        $output .= '</div>';
        
        $output .= '</div>'; // پایان news-detail-meta
        
        // عنوان
        $output .= '<h1 class="news-detail-title">' . htmlspecialchars($item->getTitle()) . '</h1>';
        
        $output .= '</div>'; // پایان news-detail-header
        
        // محتوای خبر
        $output .= '<div class="news-detail-body">';
        $output .= '<div class="news-detail-text">';
        $output .= $item->getContent();
        $output .= '</div>';
        
        // گالری تصاویر
        if (!empty($item->getGallery())) {
            $output .= $this->renderGallery($item->getGallery());
        }
        
        $output .= '</div>'; // پایان news-detail-body
        
        // فوتر خبر
        $output .= '<div class="news-detail-footer">';
        
        // برچسب‌ها
        $output .= '<div class="news-detail-tags">';
        $tags = $item->getTags();
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $output .= '<a href="?tag=' . urlencode($tag) . '" class="news-tag">' . htmlspecialchars($tag) . '</a>';
            }
        } else {
            $output .= '<a href="#" class="news-tag">باشگاه</a>';
            $output .= '<a href="#" class="news-tag">آرین رزم</a>';
            $output .= '<a href="#" class="news-tag">ورزش</a>';
        }
        $output .= '</div>'; // پایان news-detail-tags
        
        // اشتراک‌گذاری
        $output .= '<div class="news-detail-share">';
        $output .= '<span class="share-text">اشتراک‌گذاری:</span>';
        $output .= '<div class="share-links">';
        $output .= '<a href="#" class="share-link share-facebook" data-type="facebook"><i class="fab fa-facebook-f"></i></a>';
        $output .= '<a href="#" class="share-link share-twitter" data-type="twitter"><i class="fab fa-twitter"></i></a>';
        $output .= '<a href="#" class="share-link share-telegram" data-type="telegram"><i class="fab fa-telegram-plane"></i></a>';
        $output .= '<a href="#" class="share-link share-whatsapp" data-type="whatsapp"><i class="fab fa-whatsapp"></i></a>';
        $output .= '</div>';
        $output .= '</div>'; // پایان news-detail-share
        
        $output .= '</div>'; // پایان news-detail-footer
        
        // نظرات
        if (!empty($options['comments'])) {
            $output .= $options['comments'];
        }
        
        $output .= '</div>'; // پایان news-detail-main
        
        // سایدبار
        if (!empty($options['sidebar'])) {
            $output .= '<div class="news-sidebar">';
            $output .= $options['sidebar'];
            $output .= '</div>';
        }
        
        $output .= '</div>'; // پایان news-detail-grid
        
        // ناوبری بین اخبار
        if (!empty($options['navigation'])) {
            $output .= $this->renderNavigation($options['navigation']);
        }
        
        $output .= '</div>'; // پایان news-detail-content
        $output .= '</div>'; // پایان news-detail-container
        
        return $output;
    }
    
    /**
     * نمایش گالری تصاویر
     * 
     * @param array $images تصاویر
     * @return string خروجی HTML
     */
    private function renderGallery($images) {
        if (empty($images)) {
            return '';
        }
        
        $output = '<div class="news-gallery">';
        $output .= '<h3 class="gallery-title">گالری تصاویر</h3>';
        $output .= '<div class="gallery-grid">';
        
        foreach ($images as $image) {
            $output .= '<div class="gallery-item">';
            $output .= '<img src="' . htmlspecialchars($image) . '" alt="تصویر گالری">';
            $output .= '</div>';
        }
        
        $output .= '</div>'; // پایان gallery-grid
        $output .= '</div>'; // پایان news-gallery
        
        return $output;
    }
    
    /**
     * نمایش ناوبری بین اخبار
     * 
     * @param array $navigation اطلاعات ناوبری
     * @return string خروجی HTML
     */
    private function renderNavigation($navigation) {
        $output = '<div class="news-detail-navigation">';
        
        // خبر قبلی
        if (!empty($navigation['prev'])) {
            $output .= '<a href="news-detail.php?id=' . $navigation['prev']['id'] . '" class="nav-link nav-prev">';
            $output .= '<i class="fas fa-chevron-right"></i>';
            $output .= '<span class="nav-title">' . htmlspecialchars($navigation['prev']['title']) . '</span>';
            $output .= '</a>';
        } else {
            $output .= '<span class="nav-link nav-prev disabled">';
            $output .= '<i class="fas fa-chevron-right"></i>';
            $output .= '<span class="nav-title">خبر قبلی</span>';
            $output .= '</span>';
        }
        
        // خبر بعدی
        if (!empty($navigation['next'])) {
            $output .= '<a href="news-detail.php?id=' . $navigation['next']['id'] . '" class="nav-link nav-next">';
            $output .= '<span class="nav-title">' . htmlspecialchars($navigation['next']['title']) . '</span>';
            $output .= '<i class="fas fa-chevron-left"></i>';
            $output .= '</a>';
        } else {
            $output .= '<span class="nav-link nav-next disabled">';
            $output .= '<span class="nav-title">خبر بعدی</span>';
            $output .= '<i class="fas fa-chevron-left"></i>';
            $output .= '</span>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * نمایش فرم
     * 
     * @param News $item خبر (برای ویرایش)
     * @param array $options گزینه‌های اضافی
     * @return string خروجی HTML
     */
    public function renderForm($item = null, array $options = []) {
        $isEdit = ($item !== null);
        $formTitle = $isEdit ? 'ویرایش خبر' : 'افزودن خبر جدید';
        $formAction = $isEdit ? 'news-edit.php?id=' . $item->getId() : 'news-add.php';
        $submitText = $isEdit ? 'به‌روزرسانی خبر' : 'افزودن خبر';
        
        $output = '<div class="news-form-container">';
        $output .= '<h2 class="form-title">' . $formTitle . '</h2>';
        
        // نمایش پیام‌ها
        if (!empty($options['messages'])) {
            foreach ($options['messages'] as $message) {
                $output .= $this->renderMessage($message['text'], $message['type']);
            }
        }
        
        $output .= '<form action="' . $formAction . '" method="post" enctype="multipart/form-data" class="news-form">';
        
        // عنوان
        $output .= '<div class="form-group">';
        $output .= '<label for="title" class="form-label">عنوان خبر</label>';
        $output .= '<input type="text" id="title" name="title" class="form-control" value="' . ($isEdit ? htmlspecialchars($item->getTitle()) : '') . '" required>';
        $output .= '</div>';
        
        // دسته‌بندی
        $output .= '<div class="form-group">';
        $output .= '<label for="category_id" class="form-label">دسته‌بندی</label>';
        $output .= '<select id="category_id" name="category_id" class="form-select">';
        
        $categories = $options['categories'] ?? [];
        foreach ($categories as $category) {
            $selected = ($isEdit && $item->getCategoryId() == $category['id']) ? ' selected' : '';
            $output .= '<option value="' . $category['id'] . '"' . $selected . '>' . htmlspecialchars($category['name']) . '</option>';
        }
        
        $output .= '</select>';
        $output .= '</div>';
        
        // تصویر شاخص
        $output .= '<div class="form-group">';
        $output .= '<label for="featured_image" class="form-label">تصویر شاخص</label>';
        
        if ($isEdit && $item->getFeaturedImage()) {
            $output .= '<div class="current-image">';
            $output .= '<img src="' . htmlspecialchars($item->getFeaturedImage()) . '" alt="تصویر فعلی" style="max-width: 200px; margin-bottom: 10px;">';
            $output .= '</div>';
        }
        
        $output .= '<input type="file" id="featured_image" name="featured_image" class="form-control">';
        $output .= '</div>';
        
        // خلاصه
        $output .= '<div class="form-group">';
        $output .= '<label for="excerpt" class="form-label">خلاصه خبر</label>';
        $output .= '<textarea id="excerpt" name="excerpt" class="form-control" rows="3">' . ($isEdit ? htmlspecialchars($item->getExcerpt()) : '') . '</textarea>';
        $output .= '</div>';
        
        // محتوا
        $output .= '<div class="form-group">';
        $output .= '<label for="content" class="form-label">محتوای خبر</label>';
        $output .= '<textarea id="content" name="content" class="form-control editor" rows="10">' . ($isEdit ? $item->getContent() : '') . '</textarea>';
        $output .= '</div>';
        
        // برچسب‌ها
        $output .= '<div class="form-group">';
        $output .= '<label for="tags" class="form-label">برچسب‌ها (با کاما جدا کنید)</label>';
        $tagsValue = $isEdit ? implode(', ', $item->getTags()) : '';
        $output .= '<input type="text" id="tags" name="tags" class="form-control" value="' . htmlspecialchars($tagsValue) . '">';
        $output .= '</div>';
        
        // تاریخ انتشار
        $output .= '<div class="form-group">';
        $output .= '<label for="publish_date" class="form-label">تاریخ انتشار</label>';
        $publishDate = $isEdit ? date('Y-m-d\TH:i', strtotime($item->getPublishDate())) : date('Y-m-d\TH:i');
        $output .= '<input type="datetime-local" id="publish_date" name="publish_date" class="form-control" value="' . $publishDate . '">';
        $output .= '</div>';
        
        // وضعیت
        $output .= '<div class="form-group">';
        $output .= '<label for="status" class="form-label">وضعیت</label>';
        $output .= '<select id="status" name="status" class="form-select">';
        $statuses = [
            'published' => 'منتشر شده',
            'draft' => 'پیش‌نویس',
            'pending' => 'در انتظار بررسی'
        ];
        
        foreach ($statuses as $value => $label) {
            $selected = ($isEdit && $item->getStatus() == $value) ? ' selected' : '';
            $output .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
        }
        
        $output .= '</select>';
        $output .= '</div>';
        
        // دکمه‌ها
        $output .= '<div class="form-buttons">';
        $output .= '<button type="submit" class="btn btn-primary">' . $submitText . '</button>';
        $output .= '<a href="news-admin.php" class="btn btn-secondary">انصراف</a>';
        $output .= '</div>';
        
        $output .= '</form>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * نمایش پیام
     * 
     * @param string $message متن پیام
     * @param string $type نوع پیام
     * @return string خروجی HTML
     */
    public function renderMessage($message, $type = 'info') {
        $validTypes = ['info', 'success', 'warning', 'error'];
        $type = in_array($type, $validTypes) ? $type : 'info';
        
        $output = '<div class="message message-' . $type . '">';
        
        $icons = [
            'info' => 'fa-info-circle',
            'success' => 'fa-check-circle',
            'warning' => 'fa-exclamation-triangle',
            'error' => 'fa-times-circle'
        ];
        
        $output .= '<i class="fas ' . $icons[$type] . '"></i>';
        $output .= '<span>' . $message . '</span>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * نمایش سایدبار اخبار
     * 
     * @param array $options گزینه‌های اضافی
     * @return string خروجی HTML
     */
    public function renderSidebar(array $options = []) {
        $output = '<div class="news-sidebar">';
        
        // اخبار اخیر
        if (!empty($options['recent_news'])) {
            $output .= '<div class="sidebar-section">';
            $output .= '<div class="sidebar-header">آخرین اخبار</div>';
            $output .= '<div class="sidebar-content">';
            $output .= '<ul class="sidebar-news-list">';
            
            foreach ($options['recent_news'] as $news) {
                $output .= '<li class="sidebar-news-item">';
                $output .= '<a href="news-detail.php?id=' . $news->getId() . '" class="sidebar-news-link">';
                $output .= '<div class="sidebar-news-title">' . htmlspecialchars($news->getTitle()) . '</div>';
                $output .= '<div class="sidebar-news-date">';
                $output .= '<i class="far fa-calendar-alt"></i> ' . $news->getFormattedPublishDate();
                $output .= '</div>';
                $output .= '</a>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        // دسته‌بندی‌ها
        if (!empty($options['categories'])) {
            $output .= '<div class="sidebar-section">';
            $output .= '<div class="sidebar-header">دسته‌بندی‌ها</div>';
            $output .= '<div class="sidebar-content">';
            $output .= '<ul class="category-list">';
            
            foreach ($options['categories'] as $category) {
                $output .= '<li class="category-item">';
                $output .= '<a href="?category=' . $category['id'] . '" class="category-link">';
                $output .= '<span>' . htmlspecialchars($category['name']) . '</span>';
                $output .= '<span class="category-count">' . $category['count'] . '</span>';
                $output .= '</a>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        // اخبار پربازدید
        if (!empty($options['popular_news'])) {
            $output .= '<div class="sidebar-section">';
            $output .= '<div class="sidebar-header">پربازدیدترین اخبار</div>';
            $output .= '<div class="sidebar-content">';
            $output .= '<ul class="sidebar-news-list">';
            
            foreach ($options['popular_news'] as $news) {
                $output .= '<li class="sidebar-news-item">';
                $output .= '<a href="news-detail.php?id=' . $news->getId() . '" class="sidebar-news-link">';
                $output .= '<div class="sidebar-news-title">' . htmlspecialchars($news->getTitle()) . '</div>';
                $output .= '<div class="sidebar-news-meta">';
                $output .= '<span><i class="far fa-eye"></i> ' . $news->getViewCount() . ' بازدید</span>';
                $output .= '</div>';
                $output .= '</a>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        // آرشیو
        if (!empty($options['archive'])) {
            $output .= '<div class="sidebar-section">';
            $output .= '<div class="sidebar-header">آرشیو اخبار</div>';
            $output .= '<div class="sidebar-content">';
            $output .= '<ul class="archive-list">';
            
            foreach ($options['archive'] as $year => $months) {
                $output .= '<li class="archive-year">';
                $output .= '<a href="?year=' . $year . '" class="archive-year-link">' . $year . '</a>';
                $output .= '<ul class="archive-months">';
                
                foreach ($months as $month => $count) {
                    $monthName = $this->getMonthName($month);
                    $output .= '<li class="archive-month">';
                    $output .= '<a href="?year=' . $year . '&month=' . $month . '" class="archive-month-link">';
                    $output .= $monthName . ' <span class="archive-count">(' . $count . ')</span>';
                    $output .= '</a>';
                    $output .= '</li>';
                }
                
                $output .= '</ul>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        // برچسب‌های محبوب
        if (!empty($options['popular_tags'])) {
            $output .= '<div class="sidebar-section">';
            $output .= '<div class="sidebar-header">برچسب‌های محبوب</div>';
            $output .= '<div class="sidebar-content">';
            $output .= '<div class="tag-cloud">';
            
            foreach ($options['popular_tags'] as $tag => $count) {
                $fontSize = 12 + min(8, $count);
                $output .= '<a href="?tag=' . urlencode($tag) . '" class="tag-cloud-item" style="font-size: ' . $fontSize . 'px;">';
                $output .= htmlspecialchars($tag);
                $output .= '</a>';
            }
            
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        // جستجو
        $output .= '<div class="sidebar-section">';
        $output .= '<div class="sidebar-header">جستجو در اخبار</div>';
        $output .= '<div class="sidebar-content">';
        $output .= '<form action="news.php" method="get" class="search-form">';
        $output .= '<div class="search-input-group">';
        $output .= '<input type="text" name="search" class="search-input" placeholder="جستجو...">';
        $output .= '<button type="submit" class="search-button"><i class="fas fa-search"></i></button>';
        $output .= '</div>';
        $output .= '</form>';
        $output .= '</div>';
        $output .= '</div>';
        
        $output .= '</div>'; // پایان news-sidebar
        
        return $output;
    }
    
    /**
     * دریافت نام ماه
     * 
     * @param int $month شماره ماه
     * @return string نام ماه
     */
    private function getMonthName($month) {
        $months = [
            1 => 'فروردین',
            2 => 'اردیبهشت',
            3 => 'خرداد',
            4 => 'تیر',
            5 => 'مرداد',
            6 => 'شهریور',
            7 => 'مهر',
            8 => 'آبان',
            9 => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند'
        ];
        
        return $months[$month] ?? '';
    }
}