<?php
class Database {
    private $server;
    private $username;
    private $password;
    private $Db;
    private $connection;

    public function __construct($server,$username,$password,$Db) {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->Db = $Db;
    }

    public function connect() {
        $this->connection = new mysqli($this->server,$this->username,$this->password,$this->Db);
        if ($this->connection->connect_error)
        {
            die("Connection failed ".$this->connection->connect_error);
        }
    }
    
    public function dbComunicate($sql) {
        $result = $this->connection->query($sql);
        if ($this->connection->error) {
            echo "Error at: ".$this->connection->error;
            return null;
        }
        return $result;
    }
    
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>