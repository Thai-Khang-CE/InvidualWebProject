<?php

require_once __DIR__ . '/../../config/database.php';

class Store
{
    private PDO $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function getStoresByProductId($productId): array
    {
        $productId = (int) $productId;

        $sql = "
            SELECT
                s.id AS store_id,
                s.name,
                s.address,
                s.city,
                s.map_url,
                psa.quantity
            FROM product_store_availability psa
            INNER JOIN stores s ON psa.store_id = s.id
            WHERE psa.product_id = :product_id
              AND psa.quantity > 0
            ORDER BY s.name ASC
        ";

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':product_id', $productId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
