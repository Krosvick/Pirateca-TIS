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

    public function __construct() {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct();
        $this->movieDAO = new moviesDAO();
        $this->movieModel = new Movie();
        $this->ratingModel = new Rating();
    }

    public function MoviePage($id) {
        $movie = $this->movieModel->find_movie($id);
        if(!$movie){
            $this->response->abort(404);
        }
        $data = [
            'Movie' => $movie
        ];

        $ratings = $this->ratingModel->search_by_movie_id($id);
        $data['Ratings'] = $ratings;
        
        //data should be constructed as 2 part array, movie and ratings
        //due to the model, data and dao interaction, this page will be the most complex yet

        return $this->render("partials/movie_page", $data);
        
    }

}
