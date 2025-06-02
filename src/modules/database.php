<?php
class Database {
    private static $instance = null;
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;
    private $charset;

    function __construct() {
        // بارگذاری تنظیمات از فایل پیکربندی
        $config = include('config.php');
        
        $this->host = $config['db_host'];
        $this->username = $config['db_username'];
        $this->password = $config['db_password'];
        $this->database = $config['db_name'];
        $this->charset = $config['db_charset'] ?? 'utf8mb4';

    }
    
    private function connect() {
        try {
            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );
            
            // تنظیم charset برای پشتیبانی از زبان فارسی
            $this->connection->set_charset($this->charset);
            
            // بررسی اتصال
            if ($this->connection->connect_error) {
                throw new \Exception("خطا در اتصال به پایگاه داده: " . $this->connection->connect_error);
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
    
    public function getConnection() {
        $this->connect();
        return $this->connection;
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }

    public function getLastInsertId() {
        return $this->connection->insert_id;
    }

    public function getAffectedRows() {
        return $this->connection->affected_rows;
    }

    public function beginTransaction() {
        $this->connection->begin_transaction();
    }

    public function commit() {
        $this->connection->commit();
    }

    public function rollback() {
        $this->connection->rollback();
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    // جلوگیری از کلون کردن
    private function __clone() {}

    // جلوگیری از unserialize کردن
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }

    // بستن اتصال هنگام از بین رفتن آبجکت
    public function __destruct() {
        $this->close();
    }
}
?>