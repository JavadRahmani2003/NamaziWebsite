/**
 * اسکریپت سیستم اخبار
 */
document.addEventListener('DOMContentLoaded', function() {
    // تنظیم لایت باکس برای تصاویر
    setupImageLightbox();
    
    // تنظیم اشتراک‌گذاری در شبکه‌های اجتماعی
    setupSocialSharing();
    
    // تنظیم نمایش تاریخ و زمان
    setupDateTimeDisplay();
    
    // تنظیم دکمه اسکرول به بالا
    setupScrollToTop();
    
    // تنظیم ویرایشگر متن برای فرم‌ها
    setupTextEditor();
    
    // تنظیم پیش‌نمایش تصویر در فرم‌ها
    setupImagePreview();
    
    // تنظیم برچسب‌ها در فرم‌ها
    setupTagsInput();
    
    // تنظیم منوی فعال
    setupActiveMenu();
    
    // تنظیم پیام‌های موقت
    setupFlashMessages();
});

/**
 * تنظیم لایت باکس برای تصاویر
 */
function setupImageLightbox() {
    // بررسی وجود تصاویر در صفحه
    const newsImages = document.querySelectorAll('.news-detail-text img, .gallery-item img');
    if (newsImages.length === 0) return;
    
    // ایجاد عناصر لایت باکس
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    
    const lightboxImg = document.createElement('img');
    lightbox.appendChild(lightboxImg);
    
    const closeBtn = document.createElement('span');
    closeBtn.className = 'lightbox-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    lightbox.appendChild(closeBtn);
    document.body.appendChild(lightbox);
    
    // اضافه کردن رویداد کلیک به تصاویر
    newsImages.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', () => {
            lightboxImg.src = img.src;
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });
    
    // بستن لایت باکس با کلیک روی خود لایت باکس
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // بستن لایت باکس با کلید ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && lightbox.style.display === 'flex') {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
}

/**
 * تنظیم اشتراک‌گذاری در شبکه‌های اجتماعی
 */
function setupSocialSharing() {
    const shareButtons = document.querySelectorAll('.news-share-link, .share-link');
    if (shareButtons.length === 0) return;
    
    shareButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            
            const type = button.getAttribute('data-type');
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            
            let shareUrl = '';
            
            switch(type) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
                case 'telegram':
                    shareUrl = `https://t.me/share/url?url=${url}&text=${title}`;
                    break;
                case 'whatsapp':
                    shareUrl = `https://api.whatsapp.com/send?text=${title} ${url}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });
}

/**
 * تنظیم نمایش تاریخ و زمان
 */
function setupDateTimeDisplay() {
    const dateElements = document.querySelectorAll('.news-date, [data-timestamp]');
    if (dateElements.length === 0) return;
    
    dateElements.forEach(element => {
        const timestamp = parseInt(element.getAttribute('data-timestamp'));
        if (!isNaN(timestamp)) {
            const date = new Date(timestamp * 1000);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            element.textContent = date.toLocaleDateString('fa-IR', options);
        }
    });
}

/**
 * تنظیم دکمه اسکرول به بالا
 */
function setupScrollToTop() {
    const scrollTopBtn = document.querySelector('.scroll-top');
    if (!scrollTopBtn) return;
    
    // نمایش دکمه هنگام اسکرول
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    });
    
    // اسکرول به بالای صفحه
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * تنظیم ویرایشگر متن برای فرم‌ها
 */
function setupTextEditor() {
    const editorElements = document.querySelectorAll('.editor');
    if (editorElements.length === 0) return;
    
    // بررسی وجود CKEditor
    if (typeof ClassicEditor !== 'undefined') {
        editorElements.forEach(element => {
            ClassicEditor
                .create(element, {
                    language: 'fa',
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'imageUpload', 'blockQuote', 'insertTable', 'undo', 'redo']
                })
                .catch(error => {
                    console.error(error);
                });
        });
    }
}

/**
 * تنظیم پیش‌نمایش تصویر در فرم‌ها
 */
function setupImagePreview() {
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    if (imageInputs.length === 0) return;
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            
            const previewContainer = document.createElement('div');
            previewContainer.className = 'image-preview';
            
            const previewImage = document.createElement('img');
            previewImage.style.maxWidth = '200px';
            previewImage.style.marginTop = '10px';
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            // حذف پیش‌نمایش قبلی
            const existingPreview = input.parentNode.querySelector('.image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            previewContainer.appendChild(previewImage);
            input.parentNode.appendChild(previewContainer);
        });
    });
}

/**
 * تنظیم برچسب‌ها در فرم‌ها
 */
function setupTagsInput() {
    const tagsInput = document.querySelector('#tags');
    if (!tagsInput) return;
    
    // بررسی وجود کتابخانه tagify
    if (typeof Tagify !== 'undefined') {
        new Tagify(tagsInput, {
            delimiters: ',',
            pattern: /^.{1,20}$/,
            maxTags: 10
        });
    } else {
        // پیاده‌سازی ساده اگر tagify موجود نباشد
        tagsInput.addEventListener('keydown', function(e) {
            if (e.key === ',' || e.key === 'Enter') {
                e.preventDefault();
                const value = this.value.trim();
                if (value) {
                    const tags = value.split(',').map(tag => tag.trim()).filter(tag => tag);
                    this.value = [...new Set(tags)].join(', ');
                }
            }
        });
    }
}

/**
 * تنظیم منوی فعال
 */
function setupActiveMenu() {
    const currentPath = window.location.pathname;
    
    // تنظیم منوی اخبار
    if (currentPath.includes('news')) {
        const newsMenuLink = document.querySelector('nav a[href*="news"]');
        if (newsMenuLink) {
            newsMenuLink.classList.add('active');
        }
    }
}

/**
 * تنظیم پیام‌های موقت
 */
function setupFlashMessages() {
    const messages = document.querySelectorAll('.message');
    if (messages.length === 0) return;
    
    messages.forEach(message => {
        // اضافه کردن دکمه بستن
        const closeBtn = document.createElement('span');
        closeBtn.innerHTML = '&times;';
        closeBtn.className = 'message-close';
        closeBtn.style.marginRight = 'auto';
        closeBtn.style.cursor = 'pointer';
        closeBtn.style.fontSize = '1.2rem';
        
        closeBtn.addEventListener('click', () => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 300);
        });
        
        message.appendChild(closeBtn);
        
        // حذف خودکار پیام بعد از 5 ثانیه
        if (!message.classList.contains('message-error')) {
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 300);
            }, 5000);
        }
    });
}