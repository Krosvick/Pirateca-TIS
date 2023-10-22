<?php

require_once 'Core/Database.php'; // Database connection???

class ratings_model{
    

    public $user_id;
    public $movie_id;
    public $rating;
    public $timestamp;

    function __construct(){
        $this->user_id=$user_id;
        $this->movie_id=$movie_id;
        $this->rating=$rating;
        $this->timestamp=$timestamp;
    }

    public function get_user_id(){
        return $this->user_id;
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