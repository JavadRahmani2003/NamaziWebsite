<?php
include("connectToDb.php");
class NewsRecieve {
    private $newsNumb;
    private $newsSum;

    public function __construct($newsNumb,$newsSum)
    {
        $this->newsNumb = $newsNumb;
        $this->newsSum = $newsSum;
    }

    public function newsGet() {
        $mydb = new Database('localhost','root','','newsdatabase');
        $mydb->connect();
        $result = $mydb->dbComunicate('SELECT * FROM news');
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                return $row;
            }
        }
    }
}