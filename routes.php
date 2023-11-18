<?php

$router->addRoute('/', 'indexController@index');
$router->addRoute('/movie/{id}', 'movieController@showMovie');
$router->addRoute('/test', 'searchController@hello');

