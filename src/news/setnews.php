<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "post";
    $targetDir = __DIR__ . "../uploads/newsimages/";
    $targetFile = $targetDir . basename($_FILES["fileimage"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $conn = new mysqli('localhost','root','','newsdatabase');
    
    if ($uploadOk == 0) {
        echo "sorry you file not uploaded!";
    } 
    else
    {
        if (move_uploaded_file($_FILES["fileimage"]["tmp_name"] , $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileimage"]["name"])) . " has been uploaded.";
            
            $stmt = $conn->prepare("INSERT INTO `news`(`pagenumb`, `header`, `thebody`, `datetime`, `imageAddress`) VALUES (? , ? , ? , ? , ? );");
            
            $pagenumber = rand(10000,99999);
            $newssum = $_POST['newssum'];
            $newstxt = $_POST['newstxt'];
            $date = date("Y-m-d H:i:s");

            $stmt->bind_param("issss",$pagenumber,$newssum,$newstxt,$date,$targetFile);
            if ($stmt->execute()) {
                echo "Query Execute!";
            }
            else
            {
                die("Error! : ".$stmt->error);
            }

            $stmt->close();
        }
    }
}
?>