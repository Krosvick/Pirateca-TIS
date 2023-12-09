<?php

namespace DAO;


use Core\Database;
use Core\DAO;
use Models\Movie;
use Exception;
use PDO;

class MoviesDAO extends DAO {


    /**
     * Class constructor for the MoviesDAO class.
     *
     * Sets the value of the 'table' property to 'movies' and calls the constructor of the parent class.
     *
     * @return void
     */
    public function __construct() {
        $this->table = 'movies';
        parent::__construct();
    }

}
