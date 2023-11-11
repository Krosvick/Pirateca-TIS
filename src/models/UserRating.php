<?php

namespace Models;

use Models\User;
use Models\Movie;

class UserRating{
    

    public User $user;
    public Movie $movie;
    public $rating;
    public $timestamp;

    function __construct(User $user,Movie $movie, $rating, $timestamp){
        $this->user= $user;
        $this->movie= $movie;
        $this->rating=$rating;
        $this->timestamp=$timestamp;
    }

    public function get_user_id(){
        return $this->user->get_user_id();
    }

    public function get_movie_id(){
        return $this->movie->get_movie_id();
    }

    public function get_rating(){
        return $this->rating;
    }

    public function get_timestamp(){
        return $this->timestamp;
    }
}