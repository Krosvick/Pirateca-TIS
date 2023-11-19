<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use Models\Rating;
use GuzzleHttp\Client;

class MoviePageController extends BaseController
{
    private $client;
    private $movieModel;
    private $ratingModel;

    public function __construct($base_url, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->movieDAO = new moviesDAO();
        $this->movieModel = new Movie();
        $this->ratingModel = new Rating();
    }

    public function MoviePage($id) {
        $movie = $this->movieModel->find_movie($id);
        $ratings = $this->ratingModel->search_by_movie_id($id);
        if(!$movie){
            $this->response->abort(404);
        }
        $data = [
            'Movie' => $movie,
            'Ratings' => $ratings
        ];

        //data should be constructed as 2 part array, movie and ratings
        //due to the model, data and dao interaction, this page will be the most complex yet

        return $this->render("partials/movie_page", $data);
        
    }

}
