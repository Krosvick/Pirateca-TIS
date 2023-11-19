<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use GuzzleHttp\Client;


class SearchController extends BaseController
{
    private $movieModel;
    
        public function __construct() {
            //call the parent constructor to get access to the properties and methods of the BaseController class
            //parent::__construct();
            
        }

        public function search(){
            $busqueda = "";
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
                $busqueda = $_POST['busqueda'];
                $this->movieModel = new Movie();
               // Cambiar parametro $search por $busqueda, por algun motivo guarda el ultimo submit de la vista
                $movie = $this->movieModel->search("ariel");
                
            }

            require 'src\views\partials\test.php';
        }
        //return $this->render("partials/test", $data);
        }       


 

