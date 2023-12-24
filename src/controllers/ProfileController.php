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

    public function ProfilePage(){
        //exception if the user is not logged in
        if(!$this->user){
            $this->response->abort(404);
        }
        //retrieve data of the user
        $user = $this->userDAO->find($id, User::class);
        //retrieve the movies of the user
        $user_movies = $this->user->getMovies($id);
        //retrieve the comments of the user

        return $this->render("register", $optionals);
    }
}


?>