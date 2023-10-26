<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
const BASE_PATH = __DIR__.'/';

require 'functions.php';
//require 'router.php';
require base_path('dao/DAO.php');
require base_path('Core/Database.php');

use DAO\moviesDAO;

$peliculas = new moviesDAO();
$peliculas_result = $peliculas->get_some(10, 0);
dd($peliculas_result);

