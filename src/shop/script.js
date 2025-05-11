// تابع تغییر تم
function toggleTheme() {
    if (document.body.id === "lighttheme") {
        document.body.id = "darktheme";
        localStorage.setItem('theme', 'dark');
        document.querySelector('.theme-toggle i').classList.remove('fa-moon');
        document.querySelector('.theme-toggle i').classList.add('fa-sun');
    } else {
        document.body.id = "lighttheme";
        localStorage.setItem('theme', 'light');
        document.querySelector('.theme-toggle i').classList.remove('fa-sun');
        document.querySelector('.theme-toggle i').classList.add('fa-moon');
    }
}

// بررسی تم ذخیره شده
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.id = 'darktheme';
        document.querySelector('.theme-toggle i').classList.remove('fa-moon');
        document.querySelector('.theme-toggle i').classList.add('fa-sun');
    }
    
    // منوی موبایل
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    mobileMenuToggle.addEventListener('click', function() {
        mainNav.classList.toggle('active');
        this.classList.toggle('active');
    });
    
    // فیلتر دسته‌بندی‌ها
    const filterCategories = document.querySelectorAll('.filter-category');
    
    filterCategories.forEach(category => {
        category.addEventListener('click', function() {
            filterCategories.forEach(cat => cat.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

// تابع باز کردن مودال نمایش سریع
function openQuickView(productId) {
    const modal = document.getElementById('quickViewModal');
    modal.style.display = 'block';
    
    // در اینجا می‌توانید اطلاعات محصول را بر اساس شناسه محصول بارگذاری کنید
    // برای مثال، از یک درخواست AJAX استفاده کنید یا از داده‌های موجود استفاده کنید
    
    // برای نمونه، اطلاعات محصول را بر اساس شناسه تنظیم می‌کنیم
    const productData = getProductData(productId);
    
    document.getElementById('modalProductCategory').textContent = productData.category;
    document.getElementById('modalProductTitle').textContent = productData.title;
    document.getElementById('modalProductPrice').textContent = productData.price;
    document.getElementById('modalProductOldPrice').textContent = productData.oldPrice || '';
    document.getElementById('modalProductOldPrice').style.display = productData.oldPrice ? 'block' : 'none';
    document.getElementById('modalProductRatingCount').textContent = `(${productData.ratingCount} نظر)`;
    document.getElementById('modalProductDescription').textContent = productData.description;
    document.getElementById('modalProductCode').textContent = `PRD-00${productId}`;
    document.getElementById('modalProductStock').textContent = productData.stock;
    document.getElementById('modalProductImage').src = `/placeholder.svg?height=500&width=500`;
    document.getElementById('productQuantity').value = 1;
}

// تابع بستن مودال نمایش سریع
function closeQuickView() {
    const modal = document.getElementById('quickViewModal');
    modal.style.display = 'none';
}

// تابع دریافت اطلاعات محصول (نمونه)
function getProductData(productId) {
    const products = {
        1: {
            category: 'لباس رزمی',
            title: 'لباس کاراته آدیداس مدل K220',
            price: '850,000 تومان',
            oldPrice: '950,000 تومان',
            ratingCount: 24,
            description: 'لباس کاراته آدیداس مدل K220 با پارچه درجه یک و دوخت عالی، مناسب برای تمرین و مسابقات. این لباس دارای وزن سبک و مقاومت بالا در برابر پارگی است و به راحتی شسته می‌شود.',
            stock: 'موجود در انبار'
        },
        2: {
            category: 'محافظ',
            title: 'دستکش بوکس چرم طبیعی 12 انس',
            price: '750,000 تومان',
            oldPrice: '820,000 تومان',
            ratingCount: 36,
            description: 'دستکش بوکس چرم طبیعی 12 انس با کیفیت عالی، مناسب برای تمرینات حرفه‌ای و مسابقات. این دستکش دارای لایه‌های محافظتی مناسب برای جلوگیری از آسیب دیدگی دست و مچ است.',
            stock: 'موجود در انبار'
        },
        3: {
            category: 'کفش',
            title: 'کفش تکواندو آدیداس مدل فایتر',
            price: '680,000 تومان',
            oldPrice: '',
            ratingCount: 18,
            description: 'کفش تکواندو آدیداس مدل فایتر با طراحی ارگونومیک و سبک، مناسب برای تمرینات و مسابقات تکواندو. این کفش دارای کف انعطاف‌پذیر و مقاوم است که به شما امکان حرکت راحت و سریع را می‌دهد.',
            stock: 'موجود در انبار'
        },
        4: {
            category: 'محافظ',
            title: 'محافظ سینه تکواندو استاندارد مسابقات',
            price: '580,000 تومان',
            oldPrice: '650,000 تومان',
            ratingCount: 12,
            description: 'محافظ سینه تکواندو استاندارد مسابقات با طراحی ارگونومیک و مواد با کیفیت، مناسب برای استفاده در مسابقات رسمی. این محافظ دارای تأییدیه فدراسیون جهانی تکواندو است.',
            stock: 'موجود در انبار'
        },
        5: {
            category: 'تجهیزات تمرینی',
            title: 'کیسه بوکس 120 سانتی‌متری چرم مصنوعی',
            price: '1,200,000 تومان',
            oldPrice: '',
            ratingCount: 8,
            description: 'کیسه بوکس 120 سانتی‌متری با روکش چرم مصنوعی مقاوم و پرشده با الیاف فشرده، مناسب برای تمرینات ضربه و افزایش قدرت. این کیسه دارای زنجیر و قلاب مقاوم برای نصب است.',
            stock: 'موجود در انبار'
        },
        6: {
            category: 'اکسسوری',
            title: 'کمربند کاراته مشکی نخی درجه یک',
            price: '120,000 تومان',
            oldPrice: '180,000 تومان',
            ratingCount: 15,
            description: 'کمربند کاراته مشکی نخی درجه یک با کیفیت عالی و دوام بالا، مناسب برای دارندگان دان. این کمربند از الیاف نخی مرغوب تهیه شده و در برابر شستشو مقاوم است.',
            stock: 'موجود در انبار'
        },
        7: {
            category: 'تجهیزات تمرینی',
            title: 'هوگو الکترونیکی تکواندو استاندارد مسابقات',
            price: '2,500,000 تومان',
            oldPrice: '',
            ratingCount: 7,
            description: 'هوگو الکترونیکی تکواندو استاندارد مسابقات با سیستم ثبت امتیاز خودکار، مناسب برای استفاده در مسابقات رسمی. این هوگو دارای تأییدیه فدراسیون جهانی تکواندو است.',
            stock: 'تنها 2 عدد باقی مانده'
        },
        8: {
            category: 'تجهیزات تمرینی',
            title: 'میت بوکس دست چرم طبیعی',
            price: '350,000 تومان',
            oldPrice: '',
            ratingCount: 22,
            description: 'میت بوکس دست با روکش چرم طبیعی و لایه‌های داخلی فوم فشرده، مناسب برای تمرینات ضربه و افزایش دقت. این میت دارای بند قابل تنظیم برای نگهداری راحت در دست است.',
            stock: 'موجود در انبار'
        }
    };
    
    return products[productId] || {
        category: 'دسته‌بندی',
        title: 'عنوان محصول',
        price: '0 تومان',
        oldPrice: '',
        ratingCount: 0,
        description: 'توضیحات محصول',
        stock: 'ناموجود'
    };
}

// تابع افزایش تعداد
function increaseQuantity() {
    const quantityInput = document.getElementById('productQuantity');
    let quantity = parseInt(quantityInput.value);
    quantityInput.value = quantity + 1;
}

// تابع کاهش تعداد
function decreaseQuantity() {
    const quantityInput = document.getElementById('productQuantity');
    let quantity = parseInt(quantityInput.value);
    if (quantity > 1) {
        quantityInput.value = quantity - 1;
    }
}

// تابع افزودن به سبد خرید از مودال
function addToCartFromModal() {
    const productTitle = document.getElementById('modalProductTitle').textContent;
    const quantity = document.getElementById('productQuantity').value;
    
    // در اینجا می‌توانید کد افزودن به سبد خرید را قرار دهید
    // برای مثال، یک درخواست AJAX به سرور ارسال کنید
    
    alert(`محصول "${productTitle}" به تعداد ${quantity} به سبد خرید اضافه شد.`);
    
    // بستن مودال
    closeQuickView();
    
    // به‌روزرسانی تعداد محصولات در سبد خرید
    updateCartCount();
}

// تابع به‌روزرسانی تعداد محصولات در سبد خرید
function updateCartCount() {
    // در اینجا می‌توانید تعداد محصولات در سبد خرید را از سرور دریافت کنید
    // برای مثال، یک درخواست AJAX به سرور ارسال کنید
    
    // برای نمونه، یک عدد تصادفی بین 1 تا 10 را نمایش می‌دهیم
    const count = Math.floor(Math.random() * 10) + 1;
    document.querySelector('.cart-count').textContent = count;
}

// بستن مودال با کلیک خارج از محتوا
window.onclick = function(event) {
    const modal = document.getElementById('quickViewModal');
    if (event.target == modal) {
        closeQuickView();
    }
}