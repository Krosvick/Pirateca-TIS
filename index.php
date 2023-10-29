<?php

//use Core\Router;
require __DIR__ . '/vendor/autoload.php';
use Core\Router;

//require_once __DIR__ . '/vendor/autoload.php';

//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->load();
//const BASE_PATH = __DIR__.'/';

//require base_path('dao/DAO.php');
//require base_path('Core/Database.php');

//require 'functions.php';
//require 'router.php';
//require base_path('dao/DAO.php');
//require base_path('Core/Database.php');

/*$router = new Router();
require base_path('routes.php');


$method = $_SERVER['REQUEST_METHOD'] ?? $_SERVER['REQUEST_METHOD'];

try {
    $router->route($uri, $method);
} catch (Exception $e) {
    echo $e->getMessage();
}*/

require 'functions.php';
require 'routes.php';
