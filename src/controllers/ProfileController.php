<?php

namespace Controllers;

use DAO\UsersDAO;
use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use Core\Application;
use GuzzleHttp\Client;


class ProfileController extends BaseController {
    
    private $user;
    private $userDAO;
    private $movieModel;
    private $movieDAO;

    public function __construct($container, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user');
        $this->movieDAO = new moviesDAO();
        $this->userDAO = new UsersDAO();
    }
}


?>