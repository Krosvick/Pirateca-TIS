<?php

namespace DAO;

use Core\Database;
use Core\DAO;
use Models\UserRating;
use Exception;
use PDO;

class RatingsDAO extends DAO {

    public function __construct() {
        $this->table = 'ratings';
        parent::__construct();
    }

    public function get_all_as_json() {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->connection->query($sql);
            $rows = $stmt->get();
            return json_encode($rows); // Return data as JSON
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
}