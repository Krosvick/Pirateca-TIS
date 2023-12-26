<?php

$app->router->get('/', 'indexController@index');

$app->router->get('/movie/{id}', 'MoviePageController@MoviePage');
$app->router->post('/movie/{id}', 'MoviePageController@MoviePage'); //rating

$app->router->get('/login', 'LoginController@index');
$app->router->post('/login', 'LoginController@index'); //logea al usuario

$app->router->get('/search', 'searchController@search');
$app->router->post('/search', 'searchController@search'); //busca peliculas

$app->router->get('/register', 'RegisterController@index');
$app->router->post('/register', 'RegisterController@index'); //registra al usuario

$app->router->delete('/movie/{id}', 'MoviePageController@MoviePage');
$app->router->get('/movie/{id}/offset/{offset}', 'MoviePageController@MoviePage');

$app->router->get('/information', 'InformationController@infopage');

$app->router->get('/profile', 'UserController@ProfilePage');
$app->router->get('/profile/likedpost', 'UserController@LikedMovies');