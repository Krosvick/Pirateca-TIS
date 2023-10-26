<?php

namespace Models;

use Models\User;

class UserRating{
    

    public User $user;
    public $movie_id;
    public $rating;
    public $timestamp;

    function __construct(User $user, $movie_id, $rating, $timestamp){
        $this->user= $user;
        $this->movie_id=$movie_id;
        $this->rating=$rating;
        $this->timestamp=$timestamp;
    }

    public function get_user_id(){
        return $this->user->get_user_id();
    }

    public function get_movie_id(){
        return  $this->movie_id;
    }

    public function get_rating(){
        return $this->rating;
    }

    public function get_timestamp(){
        return $this->timestamp;
    }
}