<?php

namespace Core;

use PDO;

class Model {
    public function __construct(
        protected ?\PDO $pdo = null) {}

    protected function query($sql, $params = []): bool|\PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        }catch (\PDOException $e) {
            echo "Error executing query: " . $e->getMessage() . PHP_EOL;
            throw $e;
        }
    }
}
