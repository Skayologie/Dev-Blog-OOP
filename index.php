<?php

use App\Modules\Admin;
use App\Modules\User;

require "./vendor/autoload.php";
$method = $_SERVER['REQUEST_METHOD'];

$url = $_POST['url'] ?? null;
$routes = [
    'Manage_Users' => [Admin::class, 'Manage_Users'],
    'login' => [User::class, 'login'],
    'register' => [User::class, 'register'],
    'archive' => [Admin::class, 'archive'],
];

if (isset($routes[$url])) {
    [$controllerClass, $methodName] = $routes[$url];
    $object = new $controllerClass();
    $object->$methodName();
} else {
    http_response_code(404);
    echo "Route not found.";
}