<?php

namespace DAO;
//PENSADO PARA UNA TABLA "Users"
//CON COLUMNAS ID, USERNAME, PASSWORD, MAIL, role, STATUS
//SUJETO A CAMBIOS

use Core\Database;
use Models\User;
use Exception;
use PDO;

    class UserDAO implements DAOInterface{
        //Clase para operaciones CRUD de usuarios
        private $connection;
        private $table = "users";

        public function __construct(){
            try
            {
                $this->connection = Database::getInstance();
            }
            catch(Exception $e)
            {
                die($e->getMessage());
            }
        }

        public function get_some($limit, $offset){
            try{
                $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
                $params = array(
                    'limit' => [$limit, PDO::PARAM_INT], //PDO::PARAM_INT es para especificar que es un entero
                    'offset' => [$offset, PDO::PARAM_INT]
                );
                $stmt = $this->connection->query($sql, $params);
                $users = $stmt->get();
                return $users;
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }

        public function get_all(){
            try{
                $sql = "SELECT * FROM {$this->table}";
                $stmt = $this->connection->query($sql);
                $users = $stmt->get();
                return $users;
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }

        public function find($id){
            try{
                $sql = "SELECT * FROM {$this->table} WHERE id = :id";
                $params = array(
                    "id" => [$id, PDO::PARAM_INT]
                );
                $stmt = $this->connection->query($sql, $params);
                $user = $stmt->find();
                return $user;
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }
        /**
        * @param User $data
        */
        //registro de usuarios
        public function register($data){
            try{
                //Sentencia SQL.
                //id autoincrementable?
                //datos deben ser proporcionados
                $sql = "INSERT INTO {$this->table} (username, password, email, role, status) VALUES (?, ?, ?, ?, ?)";
                $params = array(
                    "username" => [$data->username, PDO::PARAM_STR],
                    "password" => [$data->password, PDO::PARAM_STR],
                    "email" => [$data->email, PDO::PARAM_STR],
                    "role" => [$data->role, PDO::PARAM_STR],
                    "status" => [$data->status, PDO::PARAM_INT]
                );
                $stmt = $this->connection->query($sql, $params);
                return $stmt;
            } 
            catch (Exception $e){
                die($e->getMessage());
            }
        }
        /**
        * @param User $data
        */
        //actualizar usuarios
        public function update($data,$id){
            try{
                $sql = "UPDATE users SET username = ?, password= ?, email = ? WHERE id = :id" ; //ASUMIENDO QUE SON LAS UNICAS 3 VARIABLES MODIFICABLES
                $params = array(
                    "username" => [$data->username, PDO::PARAM_STR],
                    "password" => [$data->password, PDO::PARAM_STR],
                    "email" => [$data->email, PDO::PARAM_STR],
                    "id" => [$id, PDO::PARAM_INT]
                );
                $stmt = $this->connection->query($sql, $params);
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
                $params = array(
                    "id" => [$id, PDO::PARAM_INT]
                );
                $stmt = $this->connection->query($sql, $params);
                return $stmt;
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }

    }

    //VENTAJAS "bindParam"
    //Control del role de dato: Puedes especificar explícitamente el role de dato que se espera para cada valor vinculado. Por ejemplo, PDO::PARAM_STR para cadenas de caracteres o PDO::PARAM_INT para enteros.

//Vinculación por referencia: Puedes vincular valores por referencia, lo que significa que los valores de las variables se pueden actualizar y reflejarán esos cambios en la consulta.

//Mayor flexibilidad: Puedes reutilizar la consulta preparada con diferentes valores sin necesidad de redefinirla.