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

$app = new Application(BASE_PATH);
require base_path('routes.php');
$app->run();
