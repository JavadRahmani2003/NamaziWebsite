<?php
include("..\\modules\\connectToDb.php");
$mydb = new Database('localhost','root','','newsdatabase');
$mydb->connect();
$result = $mydb->dbComunicate("SELECT * FROM news");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
}
$mydb->close();