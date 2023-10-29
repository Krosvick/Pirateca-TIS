<?php

require __DIR__ . '/vendor/autoload.php';

use Core\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

const BASE_PATH = __DIR__.'/';

require 'functions.php';

$router = new Router();
require base_path('routes.php');


$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'] ?? $_SERVER['REQUEST_METHOD'];

try {
    $router->route($uri, $method);
} catch (Exception $e) {
    echo $e->getMessage();
}

