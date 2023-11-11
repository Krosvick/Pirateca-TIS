<?php

require __DIR__ . '/vendor/autoload.php';

use Core\Router;
use Core\Response;
use Core\Request;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

const BASE_PATH = __DIR__.'/';

require 'functions.php';

$request = new Request();
$response = new Response();
$router = new Router($request, $response);
require base_path('routes.php');

try {
    $router->dispatch();
} catch (Exception $e) {
    echo $e->getMessage();
}

