<?php

namespace Core;
use PDO;

class Database
{
    private static $instance;
    private $connection;
    public $statement; 

    private function __construct() {
        $dsn = "mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_NAME"]}";
        $options = array(
            //the attrs_ssl_ca will look for the default path if we are in linux
            PDO::MYSQL_ATTR_SSL_CA => strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? base_path("cacert.pem") : openssl_get_cert_locations()['default_cert_file'],
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );

        $this->connection = new PDO($dsn, $_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"], $options);
    }

        /**
         * Get the singleton instance of the Database class.
         *
         * @return Database The Database instance.
         */
        public static function getInstance()
        {
            if (!(self::$instance instanceof self)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Execute a database query with optional parameters.
         *
         * @param string $query The SQL query to be executed.
         * @param array $params Optional parameters for the query.
         * @return Database The Database instance.
         */
        public function query($query, $params = [])
        {
            try {
                $this->statement = $this->connection->prepare($query);

                foreach ($params as $key => $value) {
                    $this->statement->bindValue($key, $value[0], $value[1]);
                }

                $this->statement->execute();
            } catch (\PDOException $e) {
                die($e->getMessage());
            }

            return $this;
        }

        /**
         * Fetch all the results of a query and return them as an array of objects.
         *
         * @param string|null $className The class name to use for the fetched objects.
         * @return array|null An array of objects representing the results of the query, or null if there are no results.
         */
        public function get($className = null): ?array
        {
            return $this->statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className) ?? null;
        }

    //this method will return only the first result of the query
    public function find($className = null): ?object
    {
        if ($className === null) {
            $this->statement->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $this->statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
        }
        $return = $this->statement->fetch();
        return $return ? $return : null;
    }

        /**
         * Fetch a certain quantity of results from a query based on a limit and offset.
         *
         * @param int $limit The maximum number of results to fetch.
         * @param int $offset The number of results to skip before starting to fetch.
         * @param string|null $className The class name to use for the fetched objects.
         * @return array An array of objects representing the fetched results.
         */
        public function getSome($limit = 10, $offset = 0, $className = null)
        {
            $results = $this->get($className);
            $results = array_slice($results, $offset, $limit);
            return $results;
        }

        /**
         * Cascade delete records in multiple tables based on an ID.
         *
         * @param int $id The ID of the record to be deleted.
         * @param string ...$tables The names of the tables to perform the cascade delete on.
         * @return void
         */
        public function cascadeDelete($id, ...$tables)
        {
            foreach ($tables as $table) {
                $this->query("UPDATE $table SET deleted_at = NOW() WHERE id = :id", [
                    'id' => $id
                ]);
            }
        }

        /**
         * Update the deleted_at column of a record in a table based on an ID.
         *
         * @param string $table The name of the table.
         * @param int $id The ID of the record to be deleted.
         * @return void
         */
        public function Delete($table, $id)
        {
            $this->query("UPDATE $table SET deleted_at = NOW() WHERE id = :id", [
                'id' => $id
            ]);
        }

        /**
         * Update the deleted_at column of all records in a table.
         *
         * @param string $table The name of the table.
         * @return void
         */
        public function DeleteAll($table)
        {
            $this->query("UPDATE $table SET deleted_at = NOW()");
        }

        /**
         * Delete all records from a table and reset the auto-increment value.
         *
         * @param string $table The name of the table.
         * @return void
         */
        public function DeleteAllHard($table)
        {
            $this->query("DELETE FROM $table");
            $this->query("ALTER TABLE $table AUTO_INCREMENT = 1");
        }

        /**
         * Perform a join query with a pivot table and filter the results.
         *
         * @param string $table The name of the main table.
         * @param string $pivot The name of the pivot table.
         * @param string $id The column name in the main table.
         * @param string $id2 The column name in the pivot table.
         * @param array $conditions Optional conditions to filter the results.
         * @return Database The Database instance.
         */
        public function JoinFilter($table, $pivot, $id, $id2, $conditions = [])
        {
            $query = "SELECT * FROM $table WHERE id IN (SELECT $id2 FROM $pivot WHERE $id = :id)";

            if (!empty($conditions)) {
                $query .= " AND " . implode(" AND ", $conditions);
            }

            $this->statement = $this->connection->prepare($query);
            $params = ['id' => $id];

            foreach ($conditions as $key => $value) {
                $params[$key] = $value;
            }

            $this->statement->execute($params);

            return $this;
        }
    }