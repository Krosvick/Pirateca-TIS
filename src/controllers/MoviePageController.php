<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use DAO\RatingsDAO;
use Models\User;
use Models\Movie;
use Models\Rating;
use GuzzleHttp\Client;

class MoviePageController extends BaseController
{
    private $client;
    private $movieModel;
    private $ratingModel;
    #controllers can have DAOs, models should not
    private $movieDAO;
    private $ratingsDAO;

    public function __construct($base_url, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->movieDAO = new moviesDAO();
        $this->ratingsDAO = new ratingsDAO();
    }

    public function MoviePage($id) {
        //this is a more truthful oop approach
        $this->movieModel = $this->movieDAO->find($id, 'Models\Movie');
        $this->movieModel->MovieDirectorRetrieval();
        $this->movieModel->moviePosterFallback();
        $ratings = $this->ratingsDAO->getByMovie($this->movieModel);
        if(!$this->movieModel){
            $this->response->abort(404);
        }
        $data = [
            'Movie' => $this->movieModel,
            'Ratings' => $ratings
        ];

        return $this->render("partials/movie_page", $data);
        
    }

}
