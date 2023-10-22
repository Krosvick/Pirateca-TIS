<?php

require_once 'Core/Database.php'; // Database connection???

class user_model{

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $status;
    public $tipo;

    public function __construct(){
        $this->user_id=$user_id;
        $this->username=$username;
        $this->password=$password;
        $this->email=$email;
        $this->status=$status;
        $this->tipo=$tipo;
    }

    public function get_user_id(){
        return $this->user_id;
    }

    public function get_username(){
        return $this->username;
    }

    public function get_password(){
        return $this->password;
    }

    public  function get_email(){
        return $this->email;
    }

    public function get_status(){
        return $this->status;
    }

    public function get_tipo(){
        return $this->tipo;
    }

}