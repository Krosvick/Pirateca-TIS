<?php

$app->router->get('/', 'indexController@index');

$app->router->get('/movie/{id}', 'MoviePageController@MoviePage');
$app->router->post('/movie/{id}', 'MoviePageController@MoviePage'); //rating

$app->router->get('/login', 'LoginController@index');
$app->router->post('/login', 'LoginController@index'); //logea al usuario

$app->router->get('/logout', 'UserController@logout');

$app->router->get('/search/{busqueda}', 'SearchController@search');
$app->router->post('/search/{busqueda}', 'SearchController@search'); //busca peliculas

$app->router->get('/search', 'SearchController@search');
$app->router->post('/search', 'SearchController@search');

$app->router->get('/search/{busqueda}/page/{page}', 'SearchController@search');
$app->router->post('/search/{busqueda}/page/{page}', 'SearchController@search');

$app->router->get('/register', 'RegisterController@index');
$app->router->post('/register', 'RegisterController@index'); //registra al usuario

$app->router->post('/comments', 'CommentsController@createComment');
$app->router->get('/comments/{id}', 'CommentsController@getComments');
$app->router->post('/like/{id}', 'UserController@likeReview');
$app->router->post('/dislike/{id}', 'UserController@dislikeReview');
$app->router->get('/like/{id}', 'UserController@getLikeReview');


$app->router->get('/movie/{id}/delete', 'MoviePageController@DeleteMovie');
$app->router->get('/movie/{idmovie}/review/{idreview}/delete', 'MoviePageController@DeleteReview');
$app->router->get('/movie/{id}/offset/{offset}', 'MoviePageController@MoviePage');
$app->router->get('/addMovie', 'MovieController@createMovie');
$app->router->post('/addMovie', 'MovieController@createMovie');

$app->router->get('/information', 'InfoController@infoPage');

$app->router->get('/profile/{id}', 'UserController@ProfilePage');
$app->router->get('/profile/{id}/likedpost', 'UserController@LikedMovies');
$app->router->get('/profile/{id}/likedpost/{page}', 'UserController@LikedMovies');

$app->router->get('/follow/{id}', 'UserController@follow');
$app->router->get('/unfollow/{id}', 'UserController@unfollow');