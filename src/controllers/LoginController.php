<?php 

namespace Controllers;

use Core\BaseController;

class LoginController extends BaseController
{
    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
    }

    public function index()
    {
        $data = [
            'title' => 'Login'
        ];
        return $this->render("partials/login", $data);
    }
}