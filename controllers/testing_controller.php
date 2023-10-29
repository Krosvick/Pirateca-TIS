<?php

//Declaramos una cons de nombre 'BASE_PATH'
//para evitar escribir codigo largo
//luego la reutilizan como "BASE_PATH . '/directorio'"
const BASE_PATH = __DIR__.'/../';
require BASE_PATH . '/vendor/autoload.php'; //composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../'); //credenciales?
$dotenv->load();

require BASE_PATH . 'functions.php';
require BASE_PATH . 'dao/DAO.php';
require BASE_PATH . 'Core/Database.php';

use DAO\moviesDAO;

$peliculas = new moviesDAO();
$result = $peliculas->get_some(3, 0);

$result2 = $peliculas->find(6);
//Llamamos a la vista para que utilice la
//variable '$result' y la muestre en un formato
//adecuado.
require BASE_PATH . 'views/testing_page.php';