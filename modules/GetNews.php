<?php
include("connectToDb.php");
class NewsRecieve {
    public function newsGet() {
        $mydb = new Database('localhost','root','','newsdatabase');
        $mydb->connect();
        $result = $mydb->dbComunicate('SELECT * FROM news');
        return $result;
    }
    public function newsGetById($TheNewsNumber) {
        $result = new Database('localhost','root','','newsDatabase');
        $result->connect();
        $pagenumb = $result->dbComunicate("SELECT thebody FROM news where pagenumb=".$TheNewsNumber);   
        foreach ($pagenumb as $pagenumber) {
            return htmlspecialchars($pagenumber['thebody']);
        }
    }
}