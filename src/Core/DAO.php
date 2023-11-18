<?php
#this abstract dao will implement the basic crud operations, and will be extended by the other daos
//operations:
//get_some: get a number of rows from the database, with a limit and an offset
//get_all: get all the rows from the database
//find: find a row by its id
//register: insert a new row in the database
//update: update a record from the database with the given data
//delete: delete a row from the database, if the table has relations, we also delete the related rows

namespace Core;
use PDO;
use Exception;

abstract class DAO {
    protected $connection;
    protected $table;
    protected $Relations = [];

    public function __construct() {
        try {
            $this->connection = Database::getInstance();
        } catch (Exception $e) {
            die($e->getMessage());
        } 
    }


    /**
     * 
     * @param int $limit  limit search
     * @param int $offset  starting point for search
     * 
     * @return array<array>
     */


    public function get_some($limit = 10, $offset = 0) {
        try {
            $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
            $params = array(
                'limit' => [$limit, PDO::PARAM_INT],
                'offset' => [$offset, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
  
    /**
    * 
    * @return array<array>
    */

    public function get_all() {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->connection->query($sql);
            $rows = $stmt->get();
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function dummytest($busqueda){
        try {
            $sql = "SELECT * FROM movies WHERE original_title LIKE '%$busqueda%'";
           // $params = array(
            //    "id" => [$id, PDO::PARAM_INT]
            //);
            $stmt = $this->connection->query($sql, $params);
            $row = $stmt->get();
            return $row;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
     /**
      * @param int $id   
      *
      * @return array
      */

    public function find($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $params = array(
                "id" => [$id, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $row = $stmt->find();
            return $row;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * 
     * @param string $data
     * 
     * @return void
     */

    public function register($data) {
        try {
            $sql = "INSERT INTO {$this->table} (";
            $params = array();
            foreach($data as $key => $value){
                $sql .= $key . ', ';
            }
            $sql = substr($sql, 0, -2);
            $sql .= ") VALUES (";
            foreach($data as $key => $value){
                $sql .= ':' . $key . ', ';
                $params[$key] = [$value, PDO::PARAM_STR];
            }
            $sql = substr($sql, 0, -2);
            $sql .= ")";
            $stmt = $this->connection->query($sql, $params);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

     /**
      * 
      *@param int $id
      *@param string $data
      *@param array $fields
      *
      *@return void
      */

    public function update($id, $data, $fields = []) {
        try {
            if (empty($fields)) {
                return false; // No columns specified for update
            }

            $sql = "UPDATE {$this->table} SET ";
            $params = [];
            foreach ($fields as $field) {
                $paramName = ":$field";
                $sql .= "$field = $paramName, ";
                $params[$paramName] = [$data[$field], PDO::PARAM_STR];
            }
            $sql = rtrim($sql, ', ');
            $sql .= " WHERE id = :id";
            $params[':id'] = [$id, PDO::PARAM_INT];

            $this->connection->query($sql, $params);

            return true; // Success
        } catch (Exception $e) {
            // Handle the exception or log the error
            return false; // Error
        }
    }

     /**
      * @param int $id
      * 
      * @return void
      */

    public function delete($id) {
        try {
            $this->connection->query("DELETE FROM {$this->table} WHERE id = :id", [
                'id' => [$id, PDO::PARAM_INT]
            ]);
            $this->getRelations();
            $this->cascadeDelete($id, ...$this->Relations);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

  


    /**
     * 
     * @param int $id
     * @param array<string> $tables
     * 
     * @return void
     */

    private function cascadeDelete($id, ...$tables)
    {
        foreach ($tables as $table) {
            $this->connection->query("UPDATE $table SET deleted_at = NOW() WHERE id = :id", [
                'id' => $id
            ]);
        }
    }

    private function getRelations(){
        #this will read a json file called referential integrity in this sane directory
        #it will find the relations of the table and store them in an array
        $json = file_get_contents(__DIR__ . '/referential_integrity.json');
        $relations = json_decode($json, true);
        $this->Relations = $relations[$this->table];
    }

     /**
     * @return void
     */

    public function deleteAll() {
        try {
            $this->connection->query("DELETE FROM {$this->table}");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

  
}