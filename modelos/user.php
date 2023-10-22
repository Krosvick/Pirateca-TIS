<?php

require_once __DIR__ .'/Core/Database.php';

    class user_model{
        
        private $conexion;
        public $id;     //cambiar a user_id?
        public $username;
        public $password;
        public $mail;
        public $tipo;
        public $status;

        public function __construct(){
            try
            {
                $this->conexion = new Database;
                $this->conexion = this->conexion->getInstance();
            }
            catch(Exception $e)
            {
                die($e->getMessage());
            }
        }


        public function register(user $data)
	{
		try
		{
			//Sentencia SQL.
            //id autoincrementable?
			$sql = "INSERT INTO users (username,password,mail,tipo)
		        VALUES (?, ?, ?, ?)";

			$this->conexion->prepare($sql)
		     ->execute(
				array(
                    $data->username,
                    $data->password,
                    $data->mail,
                    $data->tipo,    // por default usuario normal?
                )
			);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

        public function update($data){
            try{
                $sql = "UPDATE users SET
		        username = ?,
                password = ?,
                mail = ?,
            }
        }

    }