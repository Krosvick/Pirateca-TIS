<?php

$router->get('/', 'indexController@index');
$router->get('/movie/{id}', 'MoviePageController@MoviePage');
$router->get('/test', 'searchController@search');
$router->get('/test/{search}', 'searchController@imsorry');
$router->get('/login', 'LoginController@index');
$router->post('/login', 'LoginController@index');
$router->post('/register', 'RegisterController@index');