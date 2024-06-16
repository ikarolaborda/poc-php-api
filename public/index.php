<?php


require_once __DIR__ . '/../autoload.php';

use App\Controllers\UserController;
use Core\Config;

$config = Config::get('database');
$dsn = "{$config['driver']}:{$config['database']}";
$pdo = new PDO($dsn);

$url = $_SERVER['REQUEST_URI'];
$url = trim($url, '/');
$url = explode('/', $url);

$controllerName = !empty($url[0]) ? 'App\\Controllers\\' . ucfirst($url[0]) . 'Controller' : 'App\\Controllers\\UserController';
$action = !empty($url[1]) ? $url[1] : 'index';
$id = !empty($url[2]) ? $url[2] : null;

$controller = new $controllerName($pdo);

if ($id) {
    $controller->$action($id);
} else {
    $controller->$action();
}
