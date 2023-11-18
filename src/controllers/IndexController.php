<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use GuzzleHttp\Client;

class IndexController extends BaseController
{
    private $user;
    private $movieModel;
    

    public function index()
    {
        //the client should be logged before this, hard code for now
        $this->user = new User();
        $this->user->user_id = 2;
        $this->movieModel = new Movie();

        $user_movies = $this->movieModel->find_movies($this->user->get_recommended_movies(6));

        //dao shouldnt be here, get some should be aleatory
        $peliculas = new moviesDAO();
        $result = $peliculas->get_some(3, 0);
        $result2 = $peliculas->find(6);

        $data = [
            'title' => 'Home',
            'result' => $result,
            'result2' => $result2,
            'user_movies' => $user_movies
        ];
<<<<<<< HEAD
        return $this->render("index.php", $data);
=======
        return $this->render("index", $data);
>>>>>>> 274a9e492932883a78860c9e3a94f12b3af8c199
    }
}