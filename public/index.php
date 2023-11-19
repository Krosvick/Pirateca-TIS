<?php


use Core\Router;
use Core\Response;
use Core\Request;


const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . '/vendor/autoload.php';
require 'functions.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$request = new Request();
$response = new Response();
$request->setBaseUrl(BASE_PATH);
$router = new Router($request, $response);
require base_path('routes.php');

try {
    $router->dispatch();
} catch (Exception $e) {
    echo $e->getMessage();
}

