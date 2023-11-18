<?php

$router->addRoute('/', 'indexController@index');
$router->addRoute('/movie/{id}', 'MoviePageController@MoviePage');

