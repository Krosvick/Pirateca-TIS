<?php

$app->router->get('/', 'indexController@index');

$app->router->get('/movie/{id}', 'MoviePageController@MoviePage');
$app->router->post('/movie/{id}', 'MoviePageController@MoviePage'); //rating

$app->router->get('/login', 'LoginController@index');
$app->router->post('/login', 'LoginController@index'); //logea al usuario

$app->router->get('/logout', 'UserController@NukeUser');

$app->router->get('/search/{busqueda}', 'SearchController@search');
$app->router->post('/search/{busqueda}', 'SearchController@search'); //busca peliculas

$app->router->get('/search', 'SearchController@search');
$app->router->post('/search', 'SearchController@search');

$app->router->get('/search/{busqueda}/page/{page}', 'SearchController@search');
$app->router->post('/search/{busqueda}/page/{page}', 'SearchController@search');

$app->router->get('/register', 'RegisterController@index');
$app->router->post('/register', 'RegisterController@index'); //registra al usuario

$app->router->delete('/movie/{id}', 'MoviePageController@MoviePage');
$app->router->get('/movie/{id}/offset/{offset}', 'MoviePageController@MoviePage');

$app->router->get('/information', 'InformationController@infopage');

$app->router->get('/profile', 'UserController@ProfilePage');
$app->router->get('/profile/likedpost', 'UserController@LikedMovies');