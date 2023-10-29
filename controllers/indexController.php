<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;

class indexController extends BaseController
{
    public function index()
    {
        $peliculas = new moviesDAO();
        $result = $peliculas->get_some(3, 0);
        $result2 = $peliculas->find(6);

        $data = [
            'title' => 'Home',
            'result' => $result,
            'result2' => $result2,
        ];
        $this->render('testing_page', $data);
    }
}