<?php

namespace Core;

use PDO;
use PDOException;

class Controller {


    public function __construct(
        private \PDO $pdo
    ){}
    protected function jsonResponse($data, $statusCode = 200): bool
    {
        ob_clean();

        ob_start();

        http_response_code($statusCode);
        header('Content-Type: application/json');

        $json = json_encode($data);
        if ($json === false) {
            http_response_code(500); // Internal Server Error
            $json = json_encode(['error' => 'Failed to encode JSON']);
        }

        echo $json;

        ob_end_flush();

        return true;
    }

    protected function model($model): object
    {
        $modelClass = 'App\\Models\\' . $model;

        if (!isset($this->pdo)) {
            $config = Config::get('database');
            $dsn = "{$config['driver']}:{$config['database']}";
            $this->pdo = new PDO($dsn);
        }

        try {
            return new $modelClass($this->pdo);
        } catch (PDOException $e) {
            echo "Error connecting to the database: " . $e->getMessage() . PHP_EOL;
            throw $e;
        }
    }
}
