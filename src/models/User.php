<?php

namespace Models;
use Core\Database;
use DAO\moviesDAO;
use GuzzleHttp;

class User{

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $deleted_at;
    public $role;
    private Movie $movies;

    public function __construct($user_id = null, $username = null, $password = null, $email = null, $deleted_at = null, $role = "user"){
        $this->user_id=$user_id;
        $this->username=$username;
        $this->password=$password;
        $this->email=$email;
        $this->deleted_at=$deleted_at;
        $this->role=$role;
        $this->movies = new Movie();
    }

    public function get_user_id(){
        return $this->user_id;
    }

    public function get_username(){
        return $this->username;
    }

    public function get_password(){
        return $this->password;
    }

    public  function get_email(){
        return $this->email;
    }

    public function get_deleted_at(){
        return $this->deleted_at;
    }

    public function get_role(){
        return $this->role;
    }

    public function get_recommended_movies($quantity): array{
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', 'localhost:8001/recommendations?userId='.$this->user_id.'&n='.$quantity);
        $response = json_decode($response->getBody(), true);
        $this->movies = $this->movies->find_movies($response);
        return $movies;
    }

}