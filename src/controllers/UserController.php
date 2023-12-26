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

class UserController extends BaseController{
    private $userDAO;
    public $user;

    public function __construct($container, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user');
        $this->userDAO = new UsersDAO();
    }

    public function LikedMovies(){
        //exception if the user is not logged in
        if(!$this->user){
            echo "You are not logged in";
            $this->response->abort(404);
        }
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

    public function ProfilePage(){
        if(!$this->user){
            echo "You are not logged in";
            $this->response->abort(404);
        }

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

    public function NukeUser(){
        if($this->user){
            Application::$app->logout();
            header('Location: /');
        }
        else{
            header('Location: /');
        }
    }

}
