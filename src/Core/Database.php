<?php

namespace Core;
use PDO;

/**
 * Class Database
 *
 * The Database class is responsible for establishing a database connection using PDO and setting the connection options.
 */
class Database
{
    private static $instance;
    private $connection;
    public $statement; 

    /**
     * Database constructor.
     *
     * The constructor sets up the database connection using PDO.
     * It constructs the DSN (Data Source Name) string using the values from the $_ENV superglobal array.
     * It sets the connection options, including the SSL CA certificate path and the default fetch mode.
     * It creates a new PDO instance with the DSN, username, password, and options.
     * The connection is stored in the $connection property.
     */
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
                echo $e;
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

    /**
     * Retrieves a single row from the database and returns it as an object of the specified class, or as a generic object if no class is specified.
     *
     * @param string|null $className The name of the class to use for the fetched object.
     * @return object|null An object representing a single row from the database, or null if no row is found.
     */
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

}