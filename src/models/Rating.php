<?php

namespace Models;

use Models\User;
use Models\Movie;
use DAO\RatingsDAO;
use GuzzleHttp\Client;

class Rating{

    private $id;
    private $user_id;
    private $movie_id;
    private $rating;
    private $review;

    public function __construct($id = null, $user_id = null, $movie_id = null, $rating = null, $review = null){
        $this->id = $id;
        $this->user_id = $user_id;
        $this->movie_id = $movie_id;
        $this->rating = $rating;
        $this->review = $review;
    }

    public function search_by_movie_id($movie_id){
        $ratings = $this->ratingsDAO->get_by_movie($movie_id);
        return $ratings;
    }

    private function add_rating($user_id, $rating, $movie_id, $commentary){
        //add timestamp function here
        $rating = new Rating($user_id, $rating, $movie_id, $commentary);
        $this->ratingsDAO->add($rating);
    }

    private function delete_review($user_id, $movie_id){
        $this->ratingsDAO->delete($user_id, $movie_id);
    }

    private function show_movie_ratings($movie_id){
        $ratings = $this->ratingsDAO->get_by_movie($movie_id);
        return $ratings;
    }

    private function get_all(){
        $ratings = $this->ratingsDAO->get_all();
        return $ratings;
    }

    public function post_all_ratings(){
        $client = new Client();
        $ratings = $this->get_all();
        $response = $client->request('POST', 'localhost:8001/ratings', [
            'json' => $ratings
            
        ]);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    
}