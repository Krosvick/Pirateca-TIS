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
    
    public function __construct($base_url, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->movieModel = new Movie();
    }

    public function search(){
        return $this->render("/partials/test");
    }

    public function imsorry(){
        #busqueda will get the routeparams
        $busqueda = $this->routeParams['search'];
        $movie = $this->movieModel->search($busqueda);
        if(!$movie){
            $this->response->abort(404);
        }
        $data = [
            'Movie' => $movie,
            'busqueda' => $busqueda
        ];
        return $this->render("partials/test", $data);
    }
    
}       


 

