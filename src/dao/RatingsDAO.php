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

    /**
     * Retrieves all the data from the `ratings` table.
     *
     * @return array An array of rows containing all the data from the `ratings` table.
     */
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

    /**
     * Retrieves ratings data from the database based on a given movie object.
     *
     * @param Movie $movie A Movie object representing the movie for which ratings are to be retrieved.
     * @return array An array of rows containing the ratings data for the given movie.
     */
    public function getByMovie(Movie $movie): array {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE movie_id = :movie_id LIMIT :limit";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT],
                'limit' => [10, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

     /**
     * Retrieves ratings data from the database based on a given movie object.
     *
     * @param Movie $movie A Movie object representing the movie for which ratings are to be retrieved.
     * @return array An array of rows containing the ratings data for the given movie.
     */
    public function getPagebyMovie(Movie $movie, $lastId): array {
        try {
            $rowsPerPage = 10;

            // Query to get the rows for the current page
            $sql = "SELECT * FROM {$this->table} FORCE INDEX (movie_id_id_index) WHERE movie_id = :movie_id AND id > :last_id ORDER BY id LIMIT :limit";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT],
                'last_id' => [$lastId, PDO::PARAM_INT],
                'limit' => [$rowsPerPage, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();

            // If there are no rows, return an appropriate response
            if (empty($rows)) {
                return [
                    'message' => 'No ratings found for this movie.'
                ];
            }

            //from rows replace all user_id above 908 and assign them from one in the range of 1-900
            foreach($rows as $row){
                if($row->user_id > 908){
                    $row->user_id = rand(1, 900);
                }
            } 
            $firstId = $rows[0]->id;
            $lastId = end($rows)->id;

            $lastResultSql = "SELECT id FROM (SELECT id FROM {$this->table} WHERE movie_id = :movie_id ORDER BY id DESC LIMIT 10) sub ORDER BY id ASC LIMIT 1";
            $firstIdParams = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT]
            );
            $lastResultStmt = $this->connection->query($lastResultSql, $firstIdParams);
            $lastResult = $lastResultStmt->statement->fetchColumn();

            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE movie_id = :movie_id";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $totalRows = $stmt->statement->fetchColumn();

            return [
                'rows' => $rows,
                'firstId' => $firstId,
                'lastId' => $lastId,
                'lastResults' => $lastResult,
                'totalRows' => $totalRows
            ];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function get_liked_movies($user_id, $quantity) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY user_id LIMIT :quantity";
            $params = array(
                'user_id' => [$user_id, PDO::PARAM_INT],
                'quantity' => [$quantity, PDO::PARAM_INT],
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}