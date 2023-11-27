<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use GuzzleHttp\Client;

class IndexController extends BaseController
{
    private $user;
    private $movieModel;
    private $movieDAO;

    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->movieDAO = new moviesDAO();
    }

    public function index()
    {
        //the client should be logged before this, hard code for now
        $this->user = new User();
        $this->user->set_id(2);
        $recommended_movies = $this->user->getRecommendedMoviesIds(10);
        #the array is top_movies and then the recommended movies
        $recommended_movies = $recommended_movies['top_movies'];
        $user_movies = [];
        foreach ($recommended_movies as $movie_id) {
            $movie = $this->movieDAO->find($movie_id['movie_id'], Movie::class);
            array_push($user_movies, $movie);
        }
        
        $data = [
            'title' => 'Home',
            'user_movies' => $user_movies
        ];
        return $this->render("index", $data);
    }
}