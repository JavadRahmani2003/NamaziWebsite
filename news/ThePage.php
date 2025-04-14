<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
    <title>صفحه اخبار - <?php include('..\\modules\\GetNews.php');
        $mydb = new NewsRecieve();
        $pagenumber = $mydb->newsGetbyId(); htmlspecialchars($pagenumber['header']) ?></title>
</head>
<body>
<!--Menu-->
<nav class="siteMenu">
    <div>
        <a href="index.html"><div>صفحه اصلی</div></a>
        <a style="cursor: pointer;" onclick="ToogleMenu()"><div>منو</div></a>
        <a href="shop/"><div>فروشگاه</div></a>
        <a href="news/"><div>اخبار</div></a>
        <a href="#"><div>ارتباط با ما</div></a>
        <a href="#"><div>درباره ما</div></a>
    </div>
</nav>
<div class="MobileMenu">
    <span>Menu</span>
</div>
<!--Menu-->

<div class="MainBar" style="margin-top: 70px;">
    <div class="Title">
        موضوعات روز درباره باشگاه
    </div>
    <div class="links" align="center">
        <?php
        echo '   <div class="linksTable">';
        echo '       <td>'.htmlspecialchars($pagenumber['thebody']).'</td>';
        echo '   </div>';
        ?>
    </div>
    <div class="end">
        end
    </div>
</div>

<script src="script.js"></script>
</body>
</html><!-- 

<?php
/*include("..\\modules\\connectToDb.php");
$result = new Database('localhost','root','','newsDatabase');
$result->connect();
if ($_GET['id'])
{
    
}*/
?> -->