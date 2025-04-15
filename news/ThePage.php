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
    <title>صفحه اخبار</title>
</head>
<body>
<!--Menu-->
<nav class="mysiteMenu"></nav>
<div class="MobileMenu">
    <span>Menu</span>
</div>
<div class="MainBar" style="margin-top: 70px;">
    <div class="Title">
        موضوعات روز درباره باشگاه
    </div>
    <div class="links" align="center">
        <?php        
        include("..\\modules\\GetNews.php");
        $result = new NewsRecieve;
        $pagenumber = $result->newsGetById($_GET['pagenumber']);
        echo '   <div class="linksTable">';
        echo '       <td>'.$pagenumber.'</td>';
        echo '   </div>';
        ?>
    </div>
    <div class="end">
        end
    </div>
</div>

<script src="script.js"></script>
</body>
</html>