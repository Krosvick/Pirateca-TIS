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

        

    /**
     * Adds a follower to a user in the followers table.
     *
     * @param int $follower_id The ID of the follower to be added.
     * @param int $user_id The ID of the user to whom the follower is being added.
     * @return array The result set from the executed query.
     */
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

    /**
     * Retrieves the IDs of the users that a given user is following.
     *
     * @param int $user_id The ID of the user for whom we want to retrieve the following users.
     * @return array An array containing the IDs of the users that the given user is following.
     */
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
    
    /**
     * Retrieves the IDs of the users who are following a given user.
     *
     * @param int $user_id The ID of the user for whom we want to retrieve the followers.
     * @return array An array containing the IDs of the users who are following the given user.
     */
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