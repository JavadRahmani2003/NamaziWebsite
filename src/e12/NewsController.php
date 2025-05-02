<?php
namespace Modules\News;

use Modules\News\Exceptions\NewsException;

class NewsController {
    /**
     * مخزن خبر
     */
    private $repository;
    
    /**
     * نمایش خبر
     */
    private $view;
    
    /**
     * تنظیمات
     */
    private $config;
    
    /**
     * سازنده کلاس
     */
    public function __construct() {
        $this->repository = new NewsRepository();
        $this->view = new NewsView();
        $this->config = require_once __DIR__ . '/Config.php';
    }
    
    /**
     * نمایش صفحه اصلی اخبار
     * 
     * @return string خروجی HTML
     */
    public function index() {
        try {
            // دریافت پارامترهای درخواست
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $category = isset($_GET['category']) ? intval($_GET['category']) : null;
            $search = isset($_GET['search']) ? trim($_GET['search']) : null;
            $tag = isset($_GET['tag']) ? trim($_GET['tag']) : null;
            $year = isset($_GET['year']) ? intval($_GET['year']) : null;
            $month = isset($_GET['month']) ? intval($_GET['month']) : null;
            
            // تنظیمات صفحه‌بندی
            $perPage = $this->config['news_per_page'];
            $offset = ($page - 1) * $perPage;
            
            // گزینه‌های دریافت اخبار
            $options = [
                'limit' => $perPage,
                'offset' => $offset,
                'order_by' => 'publish_date',
                'order_dir' => 'DESC',
                'status' => 'published'
            ];
            
            if ($category) {
                $options['category'] = $category;
            }
            
            // دریافت اخبار
            $news = [];
            if ($search) {
                $news = $this->repository->search($search, $options);
                $total = $this->repository->count(['search' => $search, 'status' => 'published']);
                $title = 'نتایج جستجو برای: ' . $search;
            } elseif ($tag) {
                // پیاده‌سازی جستجو بر اساس برچسب
                // این بخش نیاز به پیاده‌سازی دارد
                $news = [];
                $total = 0;
                $title = 'اخبار با برچسب: ' . $tag;
            } elseif ($year) {
                if ($month) {
                    $news = $this->repository->getArchive($year, $month, $options);
                    $total = count($news); // نیاز به بهبود دارد
                    $title = 'آرشیو اخبار: ' . $this->getMonthName($month) . ' ' . $year;
                } else {
                    $news = $this->repository->getArchive($year, null, $options);
                    $total = count($news); // نیاز به بهبود دارد
                    $title = 'آرشیو اخبار سال: ' . $year;
                }
            } elseif ($category) {
                $news = $this->repository->getByCategory($category, $options);
                $total = $this->repository->count(['category' => $category, 'status' => 'published']);
                $title = 'اخبار دسته‌بندی: ' . $this->getCategoryName($category);
            } else {
                $news = $this->repository->getAll($options);
                $total = $this->repository->count(['status' => 'published']);
                $title = 'اخبار و رویدادها';
            }
            
            // محاسبه تعداد صفحات
            $totalPages = ceil($total / $perPage);
            
            // اطلاعات صفحه‌بندی
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $total,
                'per_page' => $perPage,
                'base_url' => '?page='
            ];
            
            if ($category) {
                $pagination['base_url'] = "?category={$category}&page=";
            } elseif ($search) {
                $pagination['base_url'] = "?search=" . urlencode($search) . "&page=";
            } elseif ($tag) {
                $pagination['base_url'] = "?tag=" . urlencode($tag) . "&page=";
            } elseif ($year) {
                $pagination['base_url'] = "?year={$year}" . ($month ? "&month={$month}" : "") . "&page=";
            }
            
            // دریافت اطلاعات سایدبار
            $sidebarOptions = [
                'recent_news' => $this->repository->getRecent($this->config['recent_news_count']),
                'popular_news' => $this->repository->getPopular(5),
                'categories' => $this->getCategories(),
                'archive' => $this->getArchiveData()
            ];
            
            $sidebar = $this->view->renderSidebar($sidebarOptions);
            
            // نمایش صفحه
            return $this->view->renderList($news, [
                'title' => $title,
                'pagination' => $pagination,
                'sidebar' => $sidebar
            ]);
            
        } catch (NewsException $e) {
            return $this->view->renderMessage($e->getMessage(), 'error');
        } catch (\Exception $e) {
            return $this->view->renderMessage('خطایی در نمایش اخبار رخ داده است.', 'error');
        }
    }
    
    /**
     * نمایش جزئیات خبر
     * 
     * @param int $id شناسه خبر
     * @return string خروجی HTML
     */
    public function show($id) {
        try {
            // دریافت خبر
            $news = $this->repository->getById($id);
            
            // افزایش تعداد بازدید
            $this->repository->incrementViewCount($id);
            
            // دریافت اخبار مرتبط
            $relatedNews = $this->repository->getRelated($id, 5);
            
            // دریافت خبر قبلی و بعدی
            $navigation = $this->getNewsNavigation($id);
            
            // دریافت اطلاعات سایدبار
            $sidebarOptions = [
                'recent_news' => $this->repository->getRecent($this->config['recent_news_count']),
                'popular_news' => $this->repository->getPopular(5),
                'categories' => $this->getCategories()
            ];
            
            $sidebar = $this->view->renderSidebar($sidebarOptions);
            
            // نمایش صفحه
            return $this->view->renderDetail($news, [
                'navigation' => $navigation,
                'sidebar' => $sidebar,
                'related_news' => $relatedNews
            ]);
            
        } catch (NewsException $e) {
            return $this->view->renderMessage($e->getMessage(), 'error');
        } catch (\Exception $e) {
            return $this->view->renderMessage('خطایی در نمایش خبر رخ داده است.', 'error');
        }
    }
    
    /**
     * نمایش فرم افزودن خبر
     * 
     * @return string خروجی HTML
     */
    public function create() {
        try {
            // بررسی دسترسی
            $this->checkPermission();
            
            // دریافت دسته‌بندی‌ها
            $categories = $this->getCategories();
            
            // نمایش فرم
            return $this->view->renderForm(null, [
                'categories' => $categories
            ]);
            
        } catch (NewsException $e) {
            return $this->view->renderMessage($e->getMessage(), 'error');
        } catch (\Exception $e) {
            return $this->view->renderMessage('خطایی در نمایش فرم افزودن خبر رخ داده است.', 'error');
        }
    }
    
    /**
     * ذخیره خبر جدید
     * 
     * @return string خروجی HTML
     */
    public function store() {
        try {
            // بررسی دسترسی
            $this->checkPermission();
            
            // دریافت داده‌های فرم
            $data = $this->getFormData();
            
            // آپلود تصویر
            if (!empty($_FILES['featured_image']['tmp_name'])) {
                $data['featured_image'] = $this->uploadImage('featured_image');
            }
            
            // ایجاد خبر جدید
            $newsId = $this->repository->create($data);
            
            // پیام موفقیت
            return $this->view->renderMessage('خبر با موفقیت ایجاد شد.', 'success');
            
        } catch (NewsException $e) {
            return $this->view->renderMessage($e->getMessage(), 'error');
        } catch (\Exception $e) {
            return $this->view->renderMessage('خطایی در ذخیره خبر رخ داده است.', 'error');
        }
    }
    
    /**
     * نمایش فرم ویرایش خبر
     * 
     * @param int $id شناسه خبر
     * @return string خروجی HTML
     */
    public function edit($id) {
        try {
            // بررسی دسترسی
            $this->checkPermission();
            
            // دریافت خبر
            $news = $this->repository->getById($id);
            
            // دریافت دسته‌بندی‌ها
            $categories = $this->getCategories();
            
            // نمایش فرم
            return $this->view->renderForm($news, [
                'categories' => $categories
            ]);
            
        } catch (NewsException $e) {
            return $this->view->renderMessage($e->getMessage(), 'error');
        } catch (\Exception $e) {
            return $this->view->renderMessage('خطایی در نمایش فرم ویرایش خبر رخ داده است.', 'error');
        }
    }
    
    /**
     * به‌روزرسانی خبر
     * 
     * @param int $id شناسه خبر
     * @return string خروجی HTML
     */
    public function update($id) {
        try {
            // بررسی دسترسی
            $this->checkPermission();
            
            // دریافت داده‌های فرم
            $data = $this->getFormData();
            
            // آپلود تصویر
            if (!empty($_FILES['featured_image']['tmp_name'])) {
                $data['featured_image'] = $this->uploadImage('featured_image');
            }
            
            // به‌روزرسانی خبر
            $this->repository->update($id, $data);
            
            // پیام موفقیت
            return $this->view->renderMessage('خبر با موفقیت به‌روزرسانی شد.', 'success');
            
        } catch (NewsException $e) {
            return $this->view->renderMessage($e->getMessage(), 'error');
        } catch (\Exception $e) {
            return $this->view->renderMessage('خطایی در به‌روزرسانی خبر رخ داده است.', 'error');
        }
    }
    
    /**
     * حذف خبر
     * 
     * @param int $id شناسه خبر
     * @return string خروجی HTML
     */
    public function delete($id) {
        try {
            // بررسی دسترسی
            $this->checkPermission();
            
            // حذف خبر
            $this->repository->delete($id);
            
            // پیام موفقیت
            return $this->view->renderMessage('خبر با موفقیت حذف شد.', 'success');
            
        } catch (NewsException $e) {
            return $this->view->renderMessage($e->getMessage(), 'error');
        } catch (\Exception $e) {
            return $this->view->renderMessage('خطایی در حذف خبر رخ داده است.', 'error');
        }
    }
    
    /**
     * دریافت داده‌های فرم
     * 
     * @return array داده‌های فرم
     */
    private function getFormData() {
        $data = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'excerpt' => $_POST['excerpt'] ?? '',
            'category_id' => isset($_POST['category_id']) ? intval($_POST['category_id']) : 1,
            'status' => $_POST['status'] ?? 'published',
            'publish_date' => $_POST['publish_date'] ?? date('Y-m-d H:i:s')
        ];
        
        // تبدیل برچسب‌ها به آرایه
        if (!empty($_POST['tags'])) {
            $tags = explode(',', $_POST['tags']);
            $data['tags'] = array_map('trim', $tags);
        }
        
        return $data;
    }
    
    /**
     * آپلود تصویر
     * 
     * @param string $fieldName نام فیلد
     * @return string مسیر تصویر
     * @throws NewsException در صورت بروز خطا
     */
    private function uploadImage($fieldName) {
        if (empty($_FILES[$fieldName]['tmp_name'])) {
            return '';
        }
        
        $file = $_FILES[$fieldName];
        $allowedTypes = $this->config['allowed_image_types'];
        $maxSize = $this->config['max_image_size'];
        $uploadPath = $this->config['news_images_path'];
        
        // بررسی نوع فایل
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension']);
        
        if (!in_array($extension, $allowedTypes)) {
            throw NewsException::fileUpload("نوع فایل مجاز نیست. فرمت‌های مجاز: " . implode(', ', $allowedTypes));
        }
        
        // بررسی حجم فایل
        if ($file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / (1024 * 1024);
            throw NewsException::fileUpload("حجم فایل بیشتر از حد مجاز است. حداکثر حجم مجاز: {$maxSizeMB} مگابایت");
        }
        
        // ایجاد مسیر آپلود اگر وجود ندارد
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // ایجاد نام فایل یکتا
        $fileName = uniqid() . '.' . $extension;
        $filePath = $uploadPath . $fileName;
        
        // آپلود فایل
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw NewsException::fileUpload("خطا در آپلود فایل.");
        }
        
        return $filePath;
    }
    
    /**
     * بررسی دسترسی کاربر
     * 
     * @throws NewsException اگر کاربر دسترسی نداشته باشد
     */
    private function checkPermission() {
        // بررسی ورود کاربر
        if (!isset($_SESSION['admin_id'])) {
            throw NewsException::permission("برای انجام این عملیات باید وارد شوید.");
        }
        
        // بررسی دسترسی مدیریت اخبار
        // این بخش باید بر اساس سیستم مجوزهای سایت پیاده‌سازی شود
    }
    
    /**
     * دریافت دسته‌بندی‌ها
     * 
     * @return array لیست دسته‌بندی‌ها
     */
    private function getCategories() {
        // این متد باید از پایگاه داده دسته‌بندی‌ها را دریافت کند
        // برای نمونه، داده‌های ثابت برگردانده می‌شود
        return [
            ['id' => 1, 'name' => 'اخبار باشگاه', 'count' => 12],
            ['id' => 2, 'name' => 'مسابقات', 'count' => 8],
            ['id' => 3, 'name' => 'آموزش‌ها', 'count' => 5],
            ['id' => 4, 'name' => 'رویدادها', 'count' => 3],
            ['id' => 5, 'name' => 'اطلاعیه‌ها', 'count' => 7]
        ];
    }
    
    /**
     * دریافت نام دسته‌بندی
     * 
     * @param int $categoryId شناسه دسته‌بندی
     * @return string نام دسته‌بندی
     */
    private function getCategoryName($categoryId) {
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            if ($category['id'] == $categoryId) {
                return $category['name'];
            }
        }
        
        return 'دسته‌بندی نامشخص';
    }
    
    /**
     * دریافت اطلاعات آرشیو
     * 
     * @return array اطلاعات آرشیو
     */
    private function getArchiveData() {
        // این متد باید از پایگاه داده اطلاعات آرشیو را دریافت کند
        // برای نمونه، داده‌های ثابت برگردانده می‌شود
        return [
            '1402' => [
                1 => 5,
                2 => 3,
                3 => 7,
                4 => 2,
                5 => 4,
                6 => 6
            ],
            '1401' => [
                10 => 8,
                11 => 5,
                12 => 9
            ]
        ];
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
    
    /**
     * دریافت اطلاعات ناوبری بین اخبار
     * 
     * @param int $newsId شناسه خبر
     * @return array اطلاعات ناوبری
     */
    private function getNewsNavigation($newsId) {
        // دریافت خبر قبلی
        $prevSql = "SELECT id, title FROM news WHERE id < {$newsId} AND status = 'published' ORDER BY id DESC LIMIT 1";
        $prevResult = Database::getInstance()->query($prevSql);
        $prev = $prevResult && $prevResult->num_rows > 0 ? $prevResult->fetch_assoc() : null;
        
        // دریافت خبر بعدی
        $nextSql = "SELECT id, title FROM news WHERE id > {$newsId} AND status = 'published' ORDER BY id ASC LIMIT 1";
        $nextResult = Database::getInstance()->query($nextSql);
        $next = $nextResult && $nextResult->num_rows > 0 ? $nextResult->fetch_assoc() : null;
        
        return [
            'prev' => $prev,
            'next' => $next
        ];
    }
}