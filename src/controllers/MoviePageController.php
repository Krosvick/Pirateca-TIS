<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use DAO\RatingsDAO;
use DAO\UsersDAO;
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
    private $userDAO;

    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct($container, $routeParams);
        $this->movieDAO = new moviesDAO();
        $this->ratingsDAO = new ratingsDAO();
        $this->userDAO = new usersDAO();
    }

    /**
     * Retrieves the movie data from the database using the moviesDAO class and the provided movie ID.
     * It also retrieves the ratings data using the ratingsDAO class.
     * If the movie data is not found, it aborts the response with a 404 status code.
     * Finally, it renders the movie page template with the retrieved movie and ratings data.
     *
     * @param int $id The movie ID from the route.
     * @return void Renders the movie page template.
     */
    public function MoviePage($id) {
        //this is a more truthful oop approach
        $this->movieModel = $this->movieDAO->find($id, 'Models\Movie');
        //this is how to validate the model, either returns true or false
        //var_dump($this->movieModel->validate(), $this->movieModel->getAllErrors());
        $this->movieModel->MovieDirectorRetrieval();
        $this->movieModel->moviePosterFallback();
        $ratings_data = $this->ratingsDAO->getByMovie($this->movieModel);
        $ratings = [];
        foreach($ratings_data as $rating_data){
            #create a rating object for each rating
            $rating = new Rating();
            $rating->set_id($rating_data->id);
            $rating->set_user($this->userDAO->find($rating_data->user_id, 'Models\User'));
            $rating->set_movie($this->movieModel);
            $rating->set_rating($rating_data->rating);
            $rating->set_review($rating_data->review);
            array_push($ratings, $rating);
        }
        dd($ratings);
        if(!$this->movieModel){
            $this->response->abort(404);
        }
        if($this->request->isDelete()){
            #lo borrai con el dao
        }
        $data = [
            'Movie' => $this->movieModel,
            'Ratings' => $ratings
        ];
        $metadata = [
            'title' => $this->movieModel->get_original_title(),
            'description' => 'Movie page',
            'cssFiles' => ['styles-movie.css'],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata,
        ];

       
        return $this->render("movie_page", $optionals);

    }

}
