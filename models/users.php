<?php

require_once 'Core/Database.php'; // Database connection???

class user_controller{

    private $conexion;
    public $id;
    public $username;
    public $password;
    public $email;
    public $status_user;
    public $type;

    public function Index(){
        $this->model = new user_model();
        $users = $this->model->getUsers();
        require_once 'Views/user/index.php';
    }

}