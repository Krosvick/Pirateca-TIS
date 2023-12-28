<?php

namespace Controllers;

use DAO\UsersDAO;
use Core\BaseController;
use DAO\moviesDAO;
use DAO\RatingsDAO;
use Models\User;
use Models\Movie;
use Core\Application;
use GuzzleHttp\Client;
use Core\Middleware\AuthMiddleware;

class UserController extends BaseController{
    private $userDAO;
    public $user;

    public function __construct($container, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user');
        $this->userDAO = new UsersDAO();
        $this->registerMiddleware(new AuthMiddleware(['likedMovies']));
    }

    public function likedMovies(){
        //exception if the user is not logged in
        $ratingsDAO = new RatingsDAO();
        $MoviesDAO = new MoviesDAO();

        $username = $this->user->get_username();
        $user_movies = $this->user->get_liked_movies($ratingsDAO, $MoviesDAO, 10);
        $data = [
            'user_movies' => $user_movies,
            'username' => $username
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.',
            'cssFiles' => [
                '' // TODO: add css files here
            ],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];

        return $this->render("likedpost", $optionals);
    }

    public function profilePage(){

        $user = Application::$app->session->get('user');
        //echo $user->get_username();
        //dd($user);

        $data = [
            'user' => $user
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.',
            'cssFiles' =>  ['styles_nav.css'],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];

        return $this->render('profile', $optionals);

    }

    public function logout(){
        if($this->user){
            Application::$app->logout();
            header('Location: /');
        }
        else{
            header('Location: /');
        }
    }
}
