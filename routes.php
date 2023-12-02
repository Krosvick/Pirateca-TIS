<?php

$app->router->get('/', 'indexController@index');
$app->router->get('/movie/{id}', 'MoviePageController@MoviePage');
$app->router->get('/test', 'searchController@search');
$app->router->get('/test/{search}', 'searchController@imsorry');
$app->router->get('/login', 'LoginController@index');
$app->router->post('/login', 'LoginController@index');
$app->router->get('/register', 'RegisterController@index');
$app->router->post('/register', 'RegisterController@index');
$app->router->delete('/movie/{id}', 'MoviePageController@MoviePage');