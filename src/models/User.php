<?php

namespace Models;
use Core\Database;
use Core\Model;
use DAO\moviesDAO;
use DAO\UsersDAO;
use GuzzleHttp;

class User extends Model{

    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $hashed_password;
    /**
     * @var string
     */
    private $first_name;
    /**
     * @var string
     */
    private $last_name;
    /**
     * @var string
     */ 
     private $created_at;
    /**
     * @var string
     */
    private $updated_at;
    /**
     * @var string
     */
    private $deleted_at;
    /**
     * @var string
     */
    private $role;
    public function __construct($id = null, $username = null, $hashed_password = null, $first_name = null, $last_name = null, $created_at = null, $updated_at = null, $deleted_at = null, $role = null){
        $this->id = $id;
        $this->username = $username;
        $this->hashed_password = $hashed_password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->role = $role;
    }

    public function get_id(){
        return $this->id;
    }
    public function set_id($id){
        $this->id = $id;
    }
    public function get_username(){
        return $this->username;
    }
    public function set_username($username){
        $this->username = $username;
    }
    public function get_hashed_password(){
        return $this->hashed_password;
    }
    public function set_hashed_password($hashed_password){
        $this->hashed_password = $hashed_password;
    }
    public function get_first_name(){
        return $this->first_name;
    }
    public function set_first_name($first_name){
        $this->first_name = $first_name;
    }
    public function get_last_name(){
        return $this->last_name;
    }
    public function set_last_name($last_name){
        $this->last_name = $last_name;
    }
    public function get_created_at(){
        return $this->created_at;
    }
    public function set_created_at($created_at){
        $this->created_at = $created_at;
    }
    public function get_updated_at(){
        return $this->updated_at;
    }
    public function set_updated_at($updated_at){
        $this->updated_at = $updated_at;
    }
    public function get_deleted_at(){
        return $this->deleted_at;
    }
    public function set_deleted_at($deleted_at){
        $this->deleted_at = $deleted_at;
    }
    public function get_role(){
        return $this->role;
    }
    public function set_role($role){
        $this->role = $role;
    }

    public static function primaryKey(){
        return 'id';
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

    public function getRecommendedMoviesIds($quantity): array{
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', 'localhost:8001/recommendations/ids?userId='.$this->id.'&n='.$quantity);
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