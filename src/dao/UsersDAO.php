<?php

namespace DAO;
//PENSADO PARA UNA TABLA "Users"
//CON COLUMNAS ID, USERNAME, PASSWORD, MAIL, role, STATUS
//SUJETO A CAMBIOS

use Core\Database;
use Core\DAO;
use Models\User;
use Exception;
use PDO;

class UsersDAO extends DAO{
        
        /**
         * UsersDAO constructor.
         *
         * This constructor initializes the 'table' property to 'users' and calls the parent constructor.
         */
    public function __construct()
    {
        $this->table = 'users';
        parent::__construct();
    }

        

    public function add_follower($follower_id, $user_id){
        $sql = "INSERT INTO followers (follower_id, followed_id) VALUES (:follower_id, :followed_id)";
        $params = array(
            'followed_id' => [$follower_id, PDO::PARAM_INT],
            'follower_id' => [$user_id, PDO::PARAM_INT]
        );
        $stmt = $this->connection->query($sql, $params);
        $rows = $stmt->get();
        return $rows;
    }

    public function get_following($user_id){
        $sql = "SELECT * FROM followers WHERE follower_id = :follower_id";
        $params = array(
            'follower_id' => [$user_id, PDO::PARAM_INT]
        );
        $stmt = $this->connection->query($sql, $params);
        $rows = $stmt->get();
        $following = array();
        foreach($rows as $row){
            $following[] = $row->followed_id;
        }
        return $following;
    }
    
    public function get_followers($user_id){
        $sql = "SELECT * FROM followers WHERE followed_id = :followed_id";
        $params = array(
            'followed_id' => [$user_id, PDO::PARAM_INT]
        );
        $stmt = $this->connection->query($sql, $params);
        $rows = $stmt->get();
        $followers = array();
        foreach($rows as $row){
            $followers[] = $row->follower_id;
        }
        return $followers;
    }
}