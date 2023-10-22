<?php

require_once 'Core/Database.php'; // Database connection???

class ratings_model{
    private $conexion;
    public $user_id;
    public $movie_id;
    public $rating;
    public $timestamp;

    function __construct(){
        try
        {
            $this->conexion = new Database();
            $this->conexion = $this->conexion->getInstance();
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }

}