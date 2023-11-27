<?php

namespace  Controllers;

use Core\BaseController;
use Core\Request;

class RegisterController extends BaseController
{
    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
    }

    public function index()
    {
        if($this->request->isPost()){
            dd($this->request->getBody());
        }
        $data = [
            'title' => 'Register'
        ];
        return $this->render("partials/register", $data);
    }
}