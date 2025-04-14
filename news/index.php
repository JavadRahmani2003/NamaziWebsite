<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
    <script>
        function loadMenu() {
    fetch('../menu.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('mysiteMenu').innerHTML = data;
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
window.onload = loadMenu;
    </script>
    <title>اخبار</title>
</head>
<body dir="rtl">

<!--Menu-->
<nav id="mysiteMenu"></nav>
<div class="MobileMenu">
    <span>Menu</span>
</div>

<div class="MainBar" style="margin-top: 70px;">
    <div class="Title">
        موضوعات روز درباره باشگاه
    </div>
    <div class="links" align="center">
        <?php
        include('..\\modules\\GetNews.php');
        $mydb = new NewsRecieve();
        $pagenumber = $mydb->newsGet();
        echo '<table border="0" class="linksTable">';
        while ($row = $pagenumber->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>'.$row['header'].'</td>';
                    echo "<td><a href=ThePage.php?pagenumber=".$row['pagenumb'].">ادامه مطلب</a></td>";
                    echo '</tr>';
                }
        echo '</table>';
        ?>
    </div>
    <div class="end">
        end
    </div>
</div>

<script src="script.js"></script>
</body>
</html>