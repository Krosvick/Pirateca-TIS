<?php

namespace DAO;
//PENSADO PARA UNA TABLA "Users"
//CON COLUMNAS ID, USERNAME, PASSWORD, MAIL, TIPO, STATUS
//SUJETO A CAMBIOS

use Core\Database;
use Models\User;
use Exception;

    class UserDAO{
        //Clase para operaciones CRUD de usuarios
        private $conexion;
        private $table = "users";

        public function __construct(){
            try
            {
                $this->conexion = new Database;
                $this->conexion = $this->conexion->getInstance();
            }
            catch(Exception $e)
            {
                die($e->getMessage());
            }
        }

    //registro de usuarios
    public function register(User $data){
		try{
			//Sentencia SQL.
            //id autoincrementable?
            //datos deben ser proporcionados
			$sql = "INSERT INTO users {$this->table} (username, password, email, tipo, status)
		        VALUES (?, ?, ?, ?, ?)";  // por default usuario normal?
            $params = array(
                $data->username,
                $data->password,
                $data->email,
                $data->tipo,
                $data->status
            );
            $stmt = $this->conexion->query($sql, $params);
            return $stmt;
		} 
        catch (Exception $e){
			die($e->getMessage());
		}
	}

    //actualizar usuarios
    public function update(User $data,$id){
        try{
            $sql = "UPDATE users SET username = ?, password= ?, email = ? WHERE id = :id" ; //ASUMIENDO QUE SON LAS UNICAS 3 VARIABLES MODIFICABLES
            $params = array(
                $data->username,
                $data->password,
                $data->email
            );
            $stmt = $this->conexion->query($sql, $params);
            return $stmt;
        }
        catch(Exception $e){
            die($e->getMessage());
        }
    }

    //borrado logico
    public function delete($id){
        try{
            $sql = "UPDATE users SET status = 0  WHERE id = :id" ; 
            $stmt = $this->conexion->query($sql, $id);
            return $stmt;
        }
        catch(Exception $e){
            die($e->getMessage());
        }
    }

    }

    //VENTAJAS "bindParam"
    //Control del tipo de dato: Puedes especificar explícitamente el tipo de dato que se espera para cada valor vinculado. Por ejemplo, PDO::PARAM_STR para cadenas de caracteres o PDO::PARAM_INT para enteros.

//Vinculación por referencia: Puedes vincular valores por referencia, lo que significa que los valores de las variables se pueden actualizar y reflejarán esos cambios en la consulta.

//Mayor flexibilidad: Puedes reutilizar la consulta preparada con diferentes valores sin necesidad de redefinirla.