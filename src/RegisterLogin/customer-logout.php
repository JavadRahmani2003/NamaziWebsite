<?php
session_start();

// پاک کردن تمام متغیرهای جلسه
session_unset();

// نابود کردن جلسه
session_destroy();

// هدایت به صفحه ورود
header('Location: customer-login.php');
exit;
?>
