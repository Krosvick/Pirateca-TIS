<?php

namespace DAO;

use Core\Database;
use Core\DAO;
use Models\Movie;
use Exception;
use PDO;

class RatingsDAO extends DAO {

    public function __construct() {
        $this->table = 'ratings';
        parent::__construct();
    }

    public function get_all_data() {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->connection->query($sql);
            $rows = $stmt->get();
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getByMovie(Movie $movie) {
        try {
            $sql = "SELECT {$this->table}.*, users.username
                    FROM {$this->table}
                    JOIN users ON {$this->table}.user_id = users.id 
                    WHERE {$this->table}.movie_id = :movie_id 
                    LIMIT :limit";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT],
                'limit' => [10, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get('Models\Rating');
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


}