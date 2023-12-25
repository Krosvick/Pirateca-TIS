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
            $firstIdSql = "SELECT id FROM (SELECT id FROM {$this->table} WHERE movie_id = :movie_id ORDER BY id DESC LIMIT 10) sub ORDER BY id ASC LIMIT 1";
            $firstIdParams = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT]
            );
            $firstIdStmt = $this->connection->query($firstIdSql, $firstIdParams);
            $lastResult = $firstIdStmt->statement->fetchColumn();

            // Query to get the rows for the current page
            $sql = "SELECT * FROM {$this->table} WHERE movie_id = :movie_id AND id > :last_id ORDER BY id LIMIT :limit";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT],
                'last_id' => [$lastId, PDO::PARAM_INT],
                'limit' => [$rowsPerPage, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();
            //from rows replace all user_id above 908 and assign them from one in the range of 1-908
            foreach($rows as $row){
                if($row->user_id > 908){
                    $row->user_id = rand(1, 908);
                }
            } 
            $firstId = $rows[0]->id;

            $lastId = end($rows)->id;

            return [
                'rows' => $rows,
                'lastId' => $lastId,
                'firstId' => $firstId,
                'lastResults' => $lastResult
            ];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}