<?php

class Database
{
    private $conn;

    public function connect()
    {
        if ($this->conn) {
            return $this->conn;
        }
        
        GeneralHelper::loadEnv(__DIR__ . '/../.env');

        $host = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
            $this->conn = new PDO($dsn, $username, $password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function closeConnection()
    {
        $this->conn = null;
    }
}
