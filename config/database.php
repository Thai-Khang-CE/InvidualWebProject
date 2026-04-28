<?php

class Database
{
    private ?PDO $connection = null;

    public function getConnection(): PDO
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $dsn = 'mysql:host=localhost;dbname=seasonal_wardrobe;charset=utf8mb4';
        $username = 'root';
        $password = '';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed.');
        }

        return $this->connection;
    }
}
