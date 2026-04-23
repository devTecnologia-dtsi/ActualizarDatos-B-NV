<?php
    class Database {
        // Use environment variables to store the database credentials
        private $host = "ip or domian";
        private $db_name = "db name";
        private $username = "user";
        private $password = "password";
        public $conn;

        public function getConnection() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->exec("set names utf8");
            } catch(PDOException $exception) {
                echo "Error de conexión: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }
?>