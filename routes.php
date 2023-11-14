<?php

$router->addRoute('/', 'indexController@index');
$router->addRoute('/movie/{id}', 'movieController@showMovie');

