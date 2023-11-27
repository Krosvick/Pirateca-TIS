<?php


use Core\Router;
use Core\Response;
use Core\Request;
use Core\Container;
use Core\Application;


const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . '/vendor/autoload.php';
require 'functions.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

#$app = new Application(BASE_PATH);
$container = new Container();
$container->set(Request::class, function () {
    $request = new Request();
    $request->setBaseUrl(BASE_PATH);
    return $request;
});
$container->set(REsponse::class, function () {
    return new Response();
});
$container->set(Router::class, function () use ($container) {
    return new Router($container);
});
$router = $container->get(Router::class);
require base_path('routes.php');

try {
    $router->dispatch();
} catch (Exception $e) {
    echo $e->getMessage();
}

