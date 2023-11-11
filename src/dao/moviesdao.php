<?php

namespace DAO;


use Core\Database;
use Core\DAO;
use Models\Movie;
use Exception;
use PDO;

class MoviesDAO extends DAO {


    public function __construct() {
        $this->table = 'movies';
        parent::__construct();
    }

}
