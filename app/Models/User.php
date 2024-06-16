<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model {

    public int $id;
    public string $name;
    public string $email;

    public string $password;

    public function getId(): int
    {
        return $this->id;
    }
    public function getAll(): bool|array
    {
        $stmt = $this->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
        return array_map(fn($user) => $user->toSafeArray(), $users);
    }

    public function getById($id) {
        $user = $this->query("SELECT * FROM users WHERE id = ?", [$id])->fetchObject(static::class);
        return $user ? $user->toSafeArray() : null;
    }

    public function create($name, $email, $password): bool|\PDOStatement
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $this->query("INSERT INTO users (name, email, password) VALUES (?, ?, ?)", [$name, $email, $hashedPassword]);
    }

    public function update($id, $name, $email, $password = null): bool|\PDOStatement
    {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            return $this->query("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?", [$name, $email, $hashedPassword, $id]);
        } else {
            return $this->query("UPDATE users SET name = ?, email = ? WHERE id = ?", [$name, $email, $id]);
        }
    }

    public function delete($id): bool|\PDOStatement
    {
        return $this->query("DELETE FROM users WHERE id = ?", [$id]);
    }

    public function toSafeArray(): array
    {
        $data = get_object_vars($this);
        unset($data['password']);
        unset($data['pdo']);
        return $data;
    }
}
