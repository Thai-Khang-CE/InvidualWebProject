<?php

require_once __DIR__ . '/../../config/database.php';

class Product
{
    private PDO $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function getProducts($categorySlug = null, $sort = 'name_asc', $limit = 8, $offset = 0): array
    {
        $allowedSorts = [
            'name_asc' => 'p.name ASC',
            'price_asc' => 'p.price ASC',
            'price_desc' => 'p.price DESC',
            'rating_desc' => 'p.rating DESC',
        ];

        $orderBy = $allowedSorts[$sort] ?? $allowedSorts['name_asc'];
        $limit = max(1, (int) $limit);
        $offset = max(0, (int) $offset);

        $sql = "
            SELECT
                p.id AS product_id,
                p.name AS product_name,
                p.slug AS product_slug,
                p.product_type,
                p.description,
                p.price,
                p.image,
                p.rating,
                p.stock,
                p.category_id,
                c.name AS category_name,
                c.slug AS category_slug
            FROM products p
            INNER JOIN categories c ON p.category_id = c.id
        ";

        if ($categorySlug !== null && $categorySlug !== '') {
            $sql .= " WHERE c.slug = :category_slug";
        }

        $sql .= " ORDER BY {$orderBy} LIMIT :limit OFFSET :offset";

        $statement = $this->connection->prepare($sql);

        if ($categorySlug !== null && $categorySlug !== '') {
            $statement->bindValue(':category_slug', $categorySlug, PDO::PARAM_STR);
        }

        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countProducts($categorySlug = null): int
    {
        $sql = "
            SELECT COUNT(*) 
            FROM products p
            INNER JOIN categories c ON p.category_id = c.id
        ";

        if ($categorySlug !== null && $categorySlug !== '') {
            $sql .= " WHERE c.slug = :category_slug";
        }

        $statement = $this->connection->prepare($sql);

        if ($categorySlug !== null && $categorySlug !== '') {
            $statement->bindValue(':category_slug', $categorySlug, PDO::PARAM_STR);
        }

        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function getProductBySlug($slug): ?array
    {
        $sql = "
            SELECT
                p.id AS product_id,
                p.name AS product_name,
                p.slug AS product_slug,
                p.product_type,
                p.description,
                p.price,
                p.image,
                p.rating,
                p.stock,
                p.category_id,
                c.name AS category_name,
                c.slug AS category_slug
            FROM products p
            INNER JOIN categories c ON p.category_id = c.id
            WHERE p.slug = :slug
            LIMIT 1
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':slug', $slug, PDO::PARAM_STR);
        $statement->execute();

        $product = $statement->fetch();

        return $product ?: null;
    }
}
