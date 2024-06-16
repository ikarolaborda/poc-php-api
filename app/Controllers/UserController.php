<?php

namespace App\Controllers;

use Core\Config;
use Core\Controller;
use App\Models\User;

class UserController extends Controller {

    public function __construct(
        private ?\PDO $pdo
    )
    {
        $config = Config::get('database');
        $dsn = "{$config['driver']}:{$config['database']}";
        $this->pdo = new \PDO($dsn);
    }
    public function index(): array | bool | string {
        $userModel = $this->model('User');
        $users = $userModel->getAll();
        return $this->jsonResponse($users);
    }

    public function show($id): bool | string {
        $userModel = $this->model('User');
        $user = $userModel->getById($id);
        if ($user) {
            return $this->jsonResponse($user);
        } else {
            return $this->jsonResponse(['error' => 'User not found'], 404);
        }
    }

    public function store(): string | bool | null {
        $data = json_decode(file_get_contents('php://input'), true);
        $userModel = $this->model('User');
        $userModel->create($data['name'], $data['email'], $data['password']);
        return $this->jsonResponse(['message' => 'User created'], 201);
    }

    public function update($id): bool | string | null {
        $data = json_decode(file_get_contents('php://input'), true);
        $userModel = $this->model('User');
        $userModel->update($id, $data['name'], $data['email'], $data['password']);
        return $this->jsonResponse(['message' => 'User updated']);
    }

    public function delete($id): bool | string | null {
        $userModel = $this->model('User');
        $userModel->delete($id);
        return $this->jsonResponse(['message' => 'User deleted'], 204);
    }
}
