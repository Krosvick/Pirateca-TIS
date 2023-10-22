<?php

require_once __DIR__ .'/Core/Database.php';
//alo commit
    class pelicula_model{
        private $conexion;
        function __construct(){
            $this->conexion = new Database();
            $this->conexion = $this->conexion->getInstance();
        }
    }
    ?>