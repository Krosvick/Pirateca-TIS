<?php

require_once 'Core/Database.php'; // Database connection???

class user_model{

    public $id;
    public $username;
    public $password;
    public $email;
    public $status;
    public $tipo;

    public function __construct(){
        $this->id=$id;
        $this->username=$username;
        $this->password=$password;
        $this->email=$email;
        $this->status=$status;
        $this->tipo=$tipo;
    }

    public function get_id(){
        return $this->id;
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