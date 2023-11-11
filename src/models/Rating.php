<?php

namespace Models;

use Models\User;
use Models\Movie;

class Rating{

    private RatingsDAO $ratingsDAO;

    public function __construct(){
        $this->ratingsDAO = new RatingsDAO();
    }

    private function add_rating($user_id, $rating, $movie_id, $commentary){
        //add timestamp function here
        $rating = new Rating($user_id, $rating, $movie_id, $commentary);
        $this->ratingsDAO->add($rating);
    }

    private function delete_review($user_id, $movie_id){
        $this->ratingsDAO->delete($user_id, $movie_id);
    }
    
}