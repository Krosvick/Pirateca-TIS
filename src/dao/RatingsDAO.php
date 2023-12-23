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
    public function getPagebyMovie(Movie $movie, $page): array {
        /**
         * Calculates the offset for pagination based on the current page number.
         *
         * @param int $page The current page number.
         * @return int The calculated offset.
         */
        $offset = ($page - 1) * 10;
        $offset = $offset < 0 ? 0 : $offset;

        try {
            $sql = "SELECT * FROM {$this->table} WHERE movie_id = :movie_id LIMIT :limit OFFSET :offset";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT],
                'limit' => [10, PDO::PARAM_INT],
                'offset' => [$offset, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();
            return $rows;

            /*
            foreach ($rows as $row) {
                $row++;
            }
            */

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


}