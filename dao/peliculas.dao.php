<?php

namespace DAO;

use Core\Database;
use PDO;

class peliculasDAO {

    private $table;

    public function __construct($table) {
        $this->table = $table;
    }

    public function getSome($limit = 10, $offset = 0) {
        try {
            $db = Database::getInstance();
            $query = $db->query("SELECT * FROM {$this->table} LIMIT $limit OFFSET $offset");
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
