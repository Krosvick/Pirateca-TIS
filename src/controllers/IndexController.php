<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use Core\Application;
use GuzzleHttp\Client;

/**
 * Class IndexController
 *
 * The IndexController class retrieves recommended movies for a user and prepares the data to be rendered in a view.
 */
class IndexController extends BaseController
{
    private $user;
    private $movieModel;
    private $movieDAO;

 
    /**
     * IndexController constructor.
     *
     * @param object $container The dependency injection container.
     * @param array $routeParams The route parameters.
     */
    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user');
        $this->movieDAO = new moviesDAO();
    }
    

    /**
     * Retrieves recommended movies for a user and prepares the data to be rendered in a view.
     *
     * @return void The rendered "index" view with the user's recommended movies and metadata.
     */
    public function index()
    {
        //the client should be logged before this, if not it will be a guest user with id 1
        if (!$this->user) {
            $this->user = new User();
            $this->user->set_id(1);
        }

        $recommended_movies = $this->user->getRecommendedMoviesIds(10);
        $recommended_movies = $recommended_movies['top_movies'];
        $user_movies = [];
        $movies_to_update = [];

        foreach ($recommended_movies as $movie_id) {
            $movie = $this->movieDAO->find($movie_id['movie_id'], Movie::class);
            if($movie !== null) {
                array_push($movies_to_update, $movie);
            }
        }
        $movie = new Movie(); //we need to create a movie object to use the moviePosterFallback method

        $movie->moviePosterFallbackArray($this->movieDAO, $movies_to_update);

        foreach ($movies_to_update as $movie) {
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