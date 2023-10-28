<?php
const BASE_PATH = __DIR__.'/../';
require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

require BASE_PATH . 'functions.php';
require BASE_PATH . 'dao/DAO.php';
require BASE_PATH . 'Core/Database.php';

use DAO\moviesDAO;

$peliculas = new moviesDAO();
$result = $peliculas->get_some(3, 0);

require BASE_PATH . 'views/testing_page.php';