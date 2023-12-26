<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use Core\Application;
use GuzzleHttp\Client;

class IndexController extends BaseController
{
    private $user;
    private $movieModel;
    private $movieDAO;


 
    /**
     * IndexController constructor.
     *
     * @param mixed $container The dependency injection container.
     * @param mixed $routeParams The route parameters.
     */
    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user');
        $this->movieDAO = new moviesDAO();
    }
    

    public function index()
    {
       //the client should be logged before this, if not it will be a guest user with id 1
        if (!$this->user) {
            $this->user = new User();
            $this->user->set_id(1);
            echo "user not logged";
        }

        $recommended_movies = $this->user->getRecommendedMoviesIds(30);
        #the array is top_movies and then the recommended movies
        $recommended_movies = $recommended_movies['top_movies'];
        $user_movies = [];
        foreach ($recommended_movies as $movie_id) {
            $movie = $this->movieDAO->find($movie_id['movie_id'], Movie::class);
            $movie->movieposterfallback();
            array_push($user_movies, $movie);
        }
        $data = [
            'user_movies' => $user_movies
        ];
        $metadata = [
            'title' => 'Home',
            'description' => 'Pirateca is a website where you can find the best movies and tv shows, and also rate them and comment on them.',
            'cssFiles' => [
                'carousel.css'
            ],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];
        return $this->render("index", $optionals);
 
    }
}