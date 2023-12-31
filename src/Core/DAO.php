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

/**
 * This is an abstract class called `DAO` that serves as a base class for data access objects.
 * It provides methods for performing common database operations such as retrieving records, inserting new records, updating existing records, and deleting records.
 * The class uses an instance of the `Database` class to interact with the database.
 */
abstract class DAO {
    protected $connection;
    protected $table;
    protected $Relations = [];

    /**
     * DAO constructor.
     *
     * Initializes the 'connection' property by calling the 'getInstance' method of the 'Database' class.
     *
     * @throws Exception if an error occurs while getting the database connection instance.
     */
    public function __construct() {
        try {
            $this->connection = Database::getInstance();
        } catch (Exception $e) {
            die($e->getMessage());
        } 
    }

    /**
     * Retrieves a specified number of rows from a database table with pagination support.
     *
     * @param int $limit The maximum number of rows to retrieve. Default is 10.
     * @param int $offset The number of rows to skip before starting to retrieve. Default is 0.
     * @return array An array of rows retrieved from the database table.
     * @throws Exception if an error occurs while executing the query.
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
     * Retrieves all rows from the specified table.
     *
     * @return array An array of rows fetched from the table.
     * @throws Exception if an error occurs while executing the query.
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
     * Performs a full-text search on the "movies" table using the "MATCH AGAINST" syntax in a SQL query.
     * Retrieves a paginated list of rows that match the search query and returns the result along with some additional information.
     *
     * @param string $busqueda The search query.
     * @param int $page The page number for pagination.
     * @return array An array containing the paginated list of rows, the total number of pages, the current page number, and the IDs of the first and last rows.
     * @throws Exception if an error occurs while executing the query.
     */
    public function fulltext_search($busqueda, $page){
        try {
            $rowsPerPage = 5;
            $offset = ($page - 1) * $rowsPerPage;

            $sql = "SELECT * FROM movies WHERE MATCH (original_title, overview) AGAINST (:busqueda IN BOOLEAN MODE) LIMIT :offset, :limit";

            $params = array(
                "busqueda" => [$busqueda . "*", PDO::PARAM_STR],
                "offset" => [$offset, PDO::PARAM_INT],
                "limit" => [$rowsPerPage, PDO::PARAM_INT]
            );

            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();

            if (empty($rows)) {
                return [
                    'message' => 'No movies found for this search.'
                ];
            }
        
            $firstId = $rows[0]->id;
            $lastId = end($rows)->id;

            $sql = "SELECT COUNT(*) FROM movies WHERE MATCH (original_title, overview) AGAINST (:busqueda IN BOOLEAN MODE)";
            $params = array(
                'busqueda' => [$busqueda . "*", PDO::PARAM_STR]
            );
            $stmt = $this->connection->query($sql, $params);
            $totalRows = $stmt->statement->fetchColumn();

            $totalPages = ceil($totalRows / $rowsPerPage);

            return [
                'rows' => $rows,
                'totalPages' => $totalPages,
                'currentPage' => $page,
                'firstId' => $firstId,
                'lastId' => $lastId,
            ];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
  

    /**
     * Retrieves a single row from the database table based on the provided id value.
     *
     * @param int $id The value of the primary key to search for.
     * @param string|null $className The name of the class to instantiate the result as.
     * @return object|null The fetched row as an object of the specified class, or null if no row is found.
     * @throws Exception if an error occurs while executing the query.
     */
    public function find($id, $className = null): ?object {
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

    /**
     * Retrieves a single row from the database table based on a specific attribute value.
     *
     * @param string $value The value to search for in the specified attribute.
     * @param string $attribute The attribute to search for the value in.
     * @param string|null $className The name of the class to instantiate the result as (optional).
     * @return object|null The result of the query, either a single object matching the specified attribute value or null if no match is found.
     * @throws Exception if an error occurs while executing the query.
     */
    public function findBy($value, $attribute, $className = null): ?object {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE $attribute = :{$value}";
            $params = array(
                "{$value}" => [$value, PDO::PARAM_STR]
            );
            $stmt = $this->connection->query($sql, $params);
            $row = $stmt->find($className);
            return $row;
        } catch (Exception $e) {
            return null;
        }
    }


    /**
     * Inserts data into a database table based on the attributes of an object.
     *
     * @param object $data An object that represents the data to be inserted into the database.
     *                     It should have attributes that correspond to the columns of the table.
     * @return object The result of the query execution.
     * @throws Exception if an error occurs while executing the query.
     */
    public function register(object $data)
    {
        $attributes = $data->attributes();
        #attributes[n] is where the attributes are
        try {
            $sql = "INSERT INTO {$this->table} (" . implode(', ', array_values($attributes)) . ") VALUES (:" . implode(', :', array_values($attributes)) . ")";
            $params = array();
            foreach ($attributes as $key => $value) {
                if (method_exists($data, "get_$value")) {
                    $params[$value] = [$data->{"get_$value"}(), PDO::PARAM_STR];
                } else {
                    $params[$value] = [$data->{$value}, PDO::PARAM_STR];
                }
            }
            $stmt = $this->connection->query($sql, $params);
            return $stmt;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Insert data into a database table.
     *
     * @param array $data An associative array containing the data to be inserted into the table.
     * @return object The result of the query execution, which can be used to check if the insertion was successful.
     * @throws Exception if an error occurs while executing the query.
     */
    public function insert(array $data)
    {
        $attributes = array_keys($data);
        #attributes[n] is where the attributes are
        try {
            $sql = "INSERT INTO {$this->table} (" . implode(', ', array_values($attributes)) . ") VALUES (:" . implode(', :', array_values($attributes)) . ")";
            $params = array();
            foreach ($attributes as $key => $value) {
                $params[$value] = [$data[$value], PDO::PARAM_STR];
            }
            $stmt = $this->connection->query($sql, $params);
            return $stmt;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    

    /**
     * Updates a record in the database table.
     *
     * @param int $id The ID of the record to be updated.
     * @param object $data The data object containing the new values for the fields to be updated.
     * @param array $fields The fields to be updated in the database table.
     * @return bool Returns true if the update is successful, or false if there is an error.
     * @throws Exception if an error occurs while executing the query.
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
     * Deletes a record from the database table based on the provided ID.
     * If there are any related tables specified in the Relations array, it also performs a cascade delete on those tables.
     *
     * @param int $id The ID of the record to be deleted.
     * @return void
     * @throws Exception if an error occurs while executing the query.
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $params = array(
                "id" => [$id, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $this->getRelations();
            if (empty($this->Relations)) {
                $result = $stmt->get();
            } else {
                $this->cascadeDelete($id, ...$this->Relations);
                $result = $stmt->get();
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

  

    /**
     * Deletes related records from multiple tables based on a given movie ID.
     *
     * @param int $idMovie The ID of the movie to be deleted.
     * @param array $tables An array of tables and their corresponding ID columns.
     * @return void
     * @throws Exception if an error occurs while executing the query.
     */
    private function cascadeDelete($idMovie, ...$tables)
    {
        //i need to iterate over the keys not the values
        try {
            foreach ($tables['tables'] as $key => $id) {
                $table = $key;
                $sql = "DELETE FROM $table WHERE $id = :id";
                $params = array(
                    "id" => [$idMovie, PDO::PARAM_INT]
                );
                $stmt = $this->connection->query($sql, $params);
                $result = $stmt->get();
            }
        } catch (Exception $e) {
            die($e->getMessage());
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
        #this will read a json file called referential integrity in this same directory
        #it will find the relations of the table and store them in an array
        $json = file_get_contents(base_path('src/dao/referential_integrity.json'));
        $relations = json_decode($json, true);
        
        # Check if the table has any relations
        if (isset($relations[$this->table])) {
            $this->Relations = $relations[$this->table];
        } else {
            # If the table has no relations, set Relations to an empty array
            $this->Relations = [];
        }
    }


    /**
     * Deletes all records from the table in the database.
     * 
     * @return void
     * @throws Exception If an error occurs during the query execution.
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
     * @throws Exception If an error occurs during the query execution.
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