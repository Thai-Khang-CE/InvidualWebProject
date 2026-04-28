<?php

require_once __DIR__ . '/../../config/database.php';

class User
{
    private PDO $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function findByEmail($email): ?array
    {
        $sql = "
            SELECT
                id,
                name,
                email,
                password_hash,
                created_at
            FROM users
            WHERE email = :email
            LIMIT 1
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function createUser($name, $email, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            INSERT INTO users (name, email, password_hash)
            VALUES (:name, :email, :password_hash)
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int) $this->connection->lastInsertId();
        }

        return false;
    }

    public function updatePasswordByEmail($email, $newPassword): bool
    {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "
            UPDATE users
            SET password_hash = :password_hash
            WHERE email = :email
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function emailExists($email): bool
    {
        $sql = "
            SELECT id
            FROM users
            WHERE email = :email
            LIMIT 1
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        return (bool) $statement->fetchColumn();
    }
}
