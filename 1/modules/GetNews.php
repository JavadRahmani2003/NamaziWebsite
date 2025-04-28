<?php
include("connectToDb.php");
class NewsRecieve {
    public function returnQueryFromDb() {
        $mydb = new Database('localhost','root','','newsdatabase');
        $mydb->connect();
        $result = $mydb->dbComunicate('SELECT * FROM news');
        return $result;
    }
    public function newsGetById($pagenummber) {
        $mydb = new Database('localhost','root','','newsdatabase');
        $mydb->connect();
        $result = $mydb->dbComunicate('SELECT thebody FROM news where pagenumb='.$pagenummber);
        return $result;
    }
}