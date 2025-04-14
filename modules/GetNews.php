<?php
include("connectToDb.php");
class NewsRecieve {
    public function newsGet() {
        $mydb = new Database('localhost','root','','newsdatabase');
        $mydb->connect();
        $result = $mydb->dbComunicate('SELECT * FROM news');
        return $result;
    }
    public function newsGetbyId() {
        $pagenumb = intval($_GET['pagenumb']);
        $mydb = new Database('localhost','root','','newsdatabase');
        $mydb->connect();
        $stmt = $mydb->dbComunicate("SELECT thebody FROM news where pagenumb=?");
        $stmt->bind_param("i",$pagenumb);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $row['thebody'];
        }
        $stmt->close();
    }
}