<?php

require_once __DIR__ .'/modelos/peliculas.php';



        $objPelicula = new pelicula();
    
        function buscarPelicula($id) {
        $listaPeliculas = $objPelicula->getPeliculas();
        require 'vista.php'; // Llama a la vista para mostrar los resultados.
        //alo commit
        }
