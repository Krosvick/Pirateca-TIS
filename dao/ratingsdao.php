<?php

namespace DAO;

use Core\Database;
use Models\UserRating;
use Exception;
use PDO;

class RatingsDAO implements DAOInterface {

    private $connection;
    private $table = "ratings";

    public function __construct() {
        try {
            $this->connection = Database::getInstance();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function get_some($limit = 10, $offset = 0) {
        try {
            $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
            $params = array(
                'limit' => [$limit, PDO::PARAM_INT],
                'offset' => [$offset, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $ratings = $stmt->get();
            return $ratings;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function get_all() {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->connection->query($sql);
            $ratings = $stmt->get();
            return $ratings;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function find($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $params = array(
                "id" => [$id, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rating = $stmt->find();
            return $rating;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function find_by_user_id($user_id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
            $params = array(
                "user_id" => [$user_id, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $ratings = $stmt->get();
            return $ratings;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function find_by_user_id_and_movie_id($user_id, $movie_id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id AND movie_id = :movie_id";
            $params = array(
                "user_id" => [$user_id, PDO::PARAM_INT],
                "movie_id" => [$movie_id, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rating = $stmt->find();
            return $rating;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    /**
     * @param UserRating $data
     */
    public function register($data) {
        try {
            $sql = "INSERT INTO {$this->table} (user_id, movie_id, rating, timestamp) VALUES (:user_id, :movie_id, :rating, :timestamp)";
            $params = array(
                "user_id" => [$data->user->get_user_id(), PDO::PARAM_INT],
                "movie_id" => [$data->movie->get_movie_id(), PDO::PARAM_INT],
                "rating" => [$data->get_rating(), PDO::PARAM_INT],
                "timestamp" => [$data->get_timestamp(), PDO::PARAM_STR]
            );
            $this->connection->query($sql, $params);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    /**
     * @param UserRating $data
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET user_id = :user_id, movie_id = :movie_id, rating = :rating, timestamp = :timestamp WHERE id = :id";
            $params = array(
                "id" => [$id, PDO::PARAM_INT],
                "user_id" => [$data->user->get_user_id(), PDO::PARAM_INT],
                "movie_id" => [$data->movie->get_movie_id(), PDO::PARAM_INT],
                "rating" => [$data->get_rating(), PDO::PARAM_INT],
                "timestamp" => [$data->get_timestamp(), PDO::PARAM_STR]
            );
            $this->connection->query($sql, $params);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $params = array(
                "id" => [$id, PDO::PARAM_INT]
            );
            $this->connection->query($sql, $params);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}