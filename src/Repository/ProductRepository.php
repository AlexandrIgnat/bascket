<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Domain\Category;
use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Repository\Traits\Logger;

class ProductRepository
{
    use Logger;

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUuid(string $uuid): Product
    {
        $sql = "SELECT
                p.*,
                c.title as category_title,
            FROM products p
            JOIN categories c on p.category = categories.id
            WHERE p.uuid like $uuid";

        $row = $this->connection->fetchOne($sql);

        if (empty($row)) {
            $this->logger->error("Faild to find product, query : $sql");
            throw new \Exception('Product not found');
        }

        return $this->make($row);
    }

    public function getByCategory(int $categoryId): array
    {
        return array_map(
            static fn (array $row): Product => $this->make($row),
                $this->connection->fetchAllAssociative(
                    "SELECT
                        p.*,
                        c.title as category_title,
                    FROM products p
                    JOIN categories с on p.category = c.id
                    WHERE is_active = 1 AND p.category = $categoryId",
                )
        );
    }

    private function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            new Category(
                $row['category'],
                $row['category_title']
            ),
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
