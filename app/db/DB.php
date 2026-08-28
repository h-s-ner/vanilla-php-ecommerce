<?php

Class DB{

    protected PDO $conn;

    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/db_config.php';

        $this->conn = $this->connect();
    }

    protected function connect():PDO{
        $this->conn = new PDO(
            "mysql:host={$this->config['host']};dbname={$this->config['db_name']}",
            $this->config['user'],
            $this->config['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        return $this->conn;
    }
}

?>