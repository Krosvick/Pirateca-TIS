<?php

$router->addRoute('/', 'indexController@index');
$router->addRoute('/movie/{id}', 'MoviePageController@MoviePage');
$router->addRoute('/test', 'searchController@search');
$router->addRoute('/test/{search}', 'searchController@imsorry');
