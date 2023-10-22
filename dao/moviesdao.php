<?php
namespace DAO;
require base_path('/dao/DAO.php');


use Core\Database;
use PDO;

class moviesDAO implements DAOInterface {

    private $table;

    public function __construct($table) {
        $this->table = $table;
    }

    public function get_some($limit = 10, $offset = 0) {
        try {
            $db = Database::getInstance();
            $query = $db->query("SELECT * FROM {$this->table} LIMIT $limit OFFSET $offset");
            return $query->get();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
