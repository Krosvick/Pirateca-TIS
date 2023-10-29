<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;

class IndexController extends BaseController
{
    public function index()
    {
        $dummy_user = new User();
        $dummy_user->user_id = 1;
        $user_movies = $dummy_user->get_recommended_movies(3);

        $peliculas = new moviesDAO();
        $result = $peliculas->get_some(3, 0);
        $result2 = $peliculas->find(6);

        $data = [
            'title' => 'Home',
            'result' => $result,
            'result2' => $result2,
            'user_movies' => $user_movies
        ];
        $this->render('testing_page', $data);
    }
}