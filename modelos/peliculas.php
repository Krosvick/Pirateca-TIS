<?php

require_once __DIR__ .'/Core/Database.php';

    class pelicula_model{
        private $conexion;
        function __construct(){
            $this->conexion = new Database();
            $this->conexion = $this->conexion->getInstance();
        }
    }
    ?>