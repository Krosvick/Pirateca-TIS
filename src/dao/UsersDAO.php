<?php

namespace DAO;
//PENSADO PARA UNA TABLA "Users"
//CON COLUMNAS ID, USERNAME, PASSWORD, MAIL, role, STATUS
//SUJETO A CAMBIOS

use Core\Database;
use Core\DAO;
use Models\User;
use Exception;
use PDO;

    class UsersDAO extends DAO{
        
        /**
         * UsersDAO constructor.
         *
         * This constructor initializes the 'table' property to 'users' and calls the parent constructor.
         */
        public function __construct()
        {
            $this->table = 'users';
            parent::__construct();
        }

        
    }

    //VENTAJAS "bindParam"
    //Control del role de dato: Puedes especificar explícitamente el role de dato que se espera para cada valor vinculado. Por ejemplo, PDO::PARAM_STR para cadenas de caracteres o PDO::PARAM_INT para enteros.

//Vinculación por referencia: Puedes vincular valores por referencia, lo que significa que los valores de las variables se pueden actualizar y reflejarán esos cambios en la consulta.

//Mayor flexibilidad: Puedes reutilizar la consulta preparada con diferentes valores sin necesidad de redefinirla.