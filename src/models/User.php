<?php

namespace Models;
use Core\Database;
use DAO\moviesDAO;
use DAO\UsersDAO;
use GuzzleHttp;

class User{

    private $user_id;
    private $username;
    private $password;
    public UsersDAO $userDAO;

    public function __construct(){ //we could use user 0 as a guest user
        $this->userDAO = new UsersDAO();
        $this->user_id = 0;
        $this->username = 'guest';
        $this->password = 'guest';
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

    public function register($username, $password){
        $this->userDAO->add($username, $password);
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