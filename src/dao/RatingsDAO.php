<?php

namespace DAO;

use Core\Database;
use Core\DAO;
use Models\UserRating;
use Exception;
use PDO;

class RatingsDAO extends DAO {

    public function __construct() {
        $this->db = Database::getInstance();
    }
}