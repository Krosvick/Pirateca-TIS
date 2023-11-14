<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use GuzzleHttp\Client;

class MoviePageController extends BaseController
{
    public function index()
    {
        dd(this->request->get('id'));
    }

}
