<?php

require_once __DIR__ . '/../../config/database.php';

class Category
{
    private PDO $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function getAllCategories(): array
    {
        $sql = "
            SELECT
                id,
                name,
                slug,
                description,
                created_at
            FROM categories
            ORDER BY id ASC
        ";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getCategoryBySlug($slug): ?array
    {
        $sql = "
            SELECT
                id,
                name,
                slug,
                description,
                created_at
            FROM categories
            WHERE slug = :slug
            LIMIT 1
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':slug', $slug, PDO::PARAM_STR);
        $statement->execute();

        $category = $statement->fetch();

        return $category ?: null;
    }
}
