<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use GuzzleHttp\Client;


class SearchController extends BaseController
{
       
    
        public function __construct() {
            //call the parent constructor to get access to the properties and methods of the BaseController class
            //parent::__construct();
        
        }

        public function search(){

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
            $busqueda = $_POST['busqueda'];

            $movie = new MoviesDAO();
            $movie = $movie->dummytest($busqueda);

        require 'src\views\partials\test.php';}
        //return $this->render("partials/test", $data);
        }

        
}

 

