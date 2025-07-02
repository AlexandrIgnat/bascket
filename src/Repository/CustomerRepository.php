<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Repository\Traits\Logger;

class CustomerRepository
{
      use Logger;

      private Connection $connection;

      public function __construct(Connection $connection)
      {
            $this->connection = $connection;
      }

      public function getByUuid(string $uuid): Customer
      {
            $sql = "SELECT * FROM customers WHERE uuid like $uuid";
            $row = $this->connection->fetchOne($sql);

            if (empty($row)) {
                  $this->logger->error("Faild to find customer, query : $sql");
                  throw new \Exception('Customer not found');
            }

            return $this->make($row);
      }

      private function make(array $row): Customer
      {
            return new Customer(
                  $row['id'],
                  $row['first_name'],
                  $row['last_name'],
                  $row['middle_name'],
                  $row['email'],
            );
      }
}
