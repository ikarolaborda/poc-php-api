<?php
$pdo = new PDO('sqlite:/Users/iclaborda/Documents/Personal/php-fws-mvc-poc/db/db.sqlite');

try {
    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    if ($result->fetchColumn() === false) {
        $pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        )");
        echo "Table 'users' created successfully.\n";
    } else {
        echo "Table 'users' already exists.\n";
    }
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . PHP_EOL;
}

$stmt = $pdo->query("SELECT * FROM users LIMIT 1");
$result = $stmt->fetchAll();
var_dump($result);
