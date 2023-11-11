<?php

namespace Models;
use Core\Database;
use DAO\moviesDAO;
use GuzzleHttp;

class User{

    public $user_id;
    public $username;
    public $password;
    public UserDAO $userDAO;

    public function __construct($username, $password){ //we could use user 0 as a guest user
        $this->username = $username;
        $this->password = $password;
        $this->userDAO = new UserDAO();
    }

    public function login(){
        $user = $this->userDAO->find($this->username);
        if ($user != null){
            if ($user->password == $this->password){
                $this->user_id = $user->user_id;
                return true;
            }
        }
        return false;
    }

    public function register(){
        $user = $this->userDAO->find($this->username);
        if ($user == null){
            $this->userDAO->add($this);
            return true;
        }
        return false;
    }

    public function get_recommended_movies($quantity): array{
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', 'localhost:8001/recommendations?userId='.$this->user_id.'&n='.$quantity);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    public function get_user_movies($quantity): array{
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', 'localhost:8001/user-movies?userId='.$this->user_id.'&n='.$quantity);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    //login
    //register
    //delete account

}