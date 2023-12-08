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

    /**
     * Perform a database query to search for rows in the "movies" table where the "original_title" column contains a specific value.
     *
     * @param string $busqueda The search term to be used in the query.
     * @return array The result set containing the rows from the "movies" table that match the search term.
     */
    public function dummytest($busqueda){
        try {
            $sql = "SELECT * FROM movies WHERE original_title LIKE CONCAT('%', :busqueda, '%')";
            $params = array(
                "busqueda" => [$busqueda, PDO::PARAM_STR]
            );
            $stmt = $this->connection->query($sql, $params);
            $row = $stmt->getSome();
            return $row;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    /**
     * Perform a full-text search on the "movies" table based on a search term.
     *
     * @param string $busqueda The search term to be used in the query.
     * @return array The result set containing the rows from the "movies" table that match the search term.
     */
    public function dummytest_fulltext($busqueda){
        try {
            #search against :busqueda* 
            #in boolean mode the * is used to search for words that start with the given word
            $sql = "SELECT * FROM movies WHERE MATCH (original_title) AGAINST (:busqueda IN BOOLEAN MODE)";

            $params = array(
                "busqueda" => [$busqueda . "*", PDO::PARAM_STR]
            );

            $stmt = $this->connection->query($sql, $params);
            $row = $stmt->getSome();
            return $row;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
     /**
      * @param int $id
      * @param string $className
      *
      * @return object
      */

    public function find($id, $className = null): object {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $params = array(
                "id" => [$id, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $row = $stmt->find($className);
            return $row;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findBy($attribute, $value, $className = null): ?object {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE $attribute = :value";
            $params = array(
                "value" => [$value, PDO::PARAM_STR]
            );
            $stmt = $this->connection->query($sql, $params);
            $row = $stmt->find($className);
            return $row;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * 
     * @param object $data
     * 
     * @return Database
     */

    public function register(object $data)
    {
        $attributes = $data->attributes();
        #attributes[n] is where the attributes are
        try {
            $sql = "INSERT INTO {$this->table} (" . implode(', ', array_values($attributes)) . ") VALUES (:" . implode(', :', array_values($attributes)) . ")";
            $params = array();
            foreach ($attributes as $key => $value) {
                $params[$value] = [$data->{"get_$value"}(), PDO::PARAM_STR];
            }
            $stmt = $this->connection->query($sql, $params);
            return $stmt;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

     /**
      * 
      *@param int $id
      *@param object $data
      *@param array $fields
      *
      *@return bool
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
                #data-> will call get_ concatenated with the field name
                $params[$paramName] = [$data->{"get_$field"}(), PDO::PARAM_STR];
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

    /**
     * Retrieves the relations of a table from a JSON file.
     *
     * This method reads the contents of the 'referential_integrity.json' file and stores the relations of a table in an array.
     *
     * @return void
     */
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

    /**
     * A method that performs a database query to search for rows in a table where a specific attribute matches a given value.
     *
     * @param string $attribute The name of the attribute to match.
     * @param mixed $value The value to match against the attribute.
     * @return object The result set containing the rows from the table where the attribute matches the given value.
     */
    public function matchAttribute(string $attribute, $value) {
        try{
            $stmt = $this->connection->query("SELECT * FROM {$this->table} WHERE $attribute = :{$attribute}", [
                "{$attribute}" => [$value, PDO::PARAM_STR]
            ]);
            $result = $stmt->find();
            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}