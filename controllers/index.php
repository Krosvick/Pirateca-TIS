<?php

namespace Controllers;

use Core\BaseController;

class indexController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home',
        ];
        $this->render('index');
    }
}