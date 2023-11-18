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
            parent::__construct();
        
        }

        public function hello(){
        // a model
        $movie = new MoviesDAO();
        
        
        //$data = [
         //       'Movie' => $movie
        //];
        require 'src\views\partials\test.php';
        //return $this->render("partials/test", $data);
        }

        
}

 

