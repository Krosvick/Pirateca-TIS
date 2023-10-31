<?php

namespace DAO;


use Core\Database;
use Models\Movie;
use Exception;
use PDO;

class MoviesDAO implements DAOInterface {

    private $connection;
    private $table = "movies";

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
            $movies = $stmt->get();
            return $movies;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function get_all() {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->connection->query($sql);
            $movies = $stmt->get();
            return $movies;
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
            $movie = $stmt->find();
            return $movie;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    /**
    * @param Movie $data
    */
    public function register($data) {
        try {
            $sql = "INSERT INTO {$this->table} (original_title, overview, genres, belongs_to_collection, adult, original_language, release_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $params = array(
                "original_title" => [$data->original_title, PDO::PARAM_STR],
                "overview" => [$data->overview, PDO::PARAM_STR],
                "genres" => [$data->genres, PDO::PARAM_STR],
                "belongs_to_collection" => [$data->belongs_to_collection, PDO::PARAM_STR],
                "adult" => [$data->adult, PDO::PARAM_STR],
                "original_language" => [$data->original_language, PDO::PARAM_STR],
                "release_date" => [$data->release_date, PDO::PARAM_STR]
            );
            $stmt = $this->connection->query($sql, $params);
            return $stmt;
        } catch (Exception $e) {
            die($e->getMessage());
        }

    }
    public function update($id, $data, $columns = null) {
        try {
            if (empty($columns)) {
                return false; // No columns specified for update
            }

            $sql = "UPDATE {$this->table} SET ";
            $params = [];
            foreach ($columns as $column) {
                $paramName = ":$column";
                $sql .= "$column = $paramName, ";
                $params[$paramName] = [$data[$column], PDO::PARAM_STR];
            }
            $sql = rtrim($sql, ', ');
            $sql .= " WHERE id = :id";
            $params[':id'] = [$id, PDO::PARAM_INT];

            $this->connection->query($sql, $params);

            return true; // Success
        } catch (Exception $e) {
            // Handle the exception or log the error
            return false; // Error
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $params = array(
                "id" => [$id, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            return $stmt;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
