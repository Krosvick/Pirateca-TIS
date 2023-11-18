<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use GuzzleHttp\Client;


class SearchController extends BaseController
{
        public function __construct($base_url, $routeParams) {
            //call the parent constructor to get access to the properties and methods of the BaseController class
            parent::__construct(...func_get_args());
        
        }

        public function search(){

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
                $busqueda = $_POST['busqueda'];

                $movieDAO = new MoviesDAO();
                $movie = $movieDAO->dummytest_fulltext($busqueda);

                $data = [
                    'movieDAO' => $movieDAO,
                    'Movie' => $movie
                ];

            }
            //require 'src\views\partials\test.php';}
            return $this->render("partials/test", $data);

        }
}

 

