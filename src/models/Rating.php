<?php

namespace Models;

use Core\Model;
use Models\User;
use Models\Movie;
use DAO\RatingsDAO;
use GuzzleHttp\Client;

class Rating extends Model{

    private $id;
    private ?User $user;
    private ?Movie $movie;
    private $rating;
    private $review;

    public function __construct($id = null, $user = null, $movie = null, $rating = null, $review = null){
        $this->id = $id;
        $this->user = $user;
        $this->movie = $movie;
        $this->rating = $rating;
        $this->review = $review;
    }

    //getters and setters
    public function get_id(){
        return $this->id;
    }
    public function set_id($id){
        $this->id = $id;
    }
    public function get_user(){
        return $this->user;
    }
    public function set_user($user){
        $this->user = $user;
    }
    public function get_movie(){
        return $this->movie;
    }
    public function set_movie($movie){
        $this->movie = $movie;
    }
    public function get_rating(){
        return $this->rating;
    }
    public function set_rating($rating){
        $this->rating = $rating;
    }
    public function get_review(){
        return $this->review;
    }
    public function set_review($review){
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