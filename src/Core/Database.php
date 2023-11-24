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

    public static function getInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($query, $params = [])
    {
        try{
            $this->statement = $this->connection->prepare($query);
            #params will look like this
            /*
                $params = array(
                        'limit' => [$limit, PDO::PARAM_INT], //PDO::PARAM_INT es para especificar que es un entero
                        'offset' => [$offset, PDO::PARAM_INT]
                );
            */
            foreach ($params as $key => $value) {
                $this->statement->bindValue($key, $value[0], $value[1]);
            }

            $this->statement->execute();
        }catch(\PDOException $e){
            die($e->getMessage());
        }

        return $this;
    }

    public function get($className = null)
    {
        return $this->statement->fetchAll(PDO::FETCH_CLASS, $className);
    }

    public function find($className = null): ?object
    {
        return $this->statement->fetchObject($className);
    }

    public function getSome($limit = 10, $offset = 0, $className = null)
    {
        #method to return a certain quantity of the statement
        $results = $this->get($className);
        $results = array_slice($results, $offset, $limit);
        return $results;
    }

    public function findOrFail()
    {
        $result = $this->find();

        if (! $result) {
            abort();
        }

        return $result;
    }
    
    #cascadeDelete will possible receive more than 1 table variable and an id
    public function cascadeDelete($id, ...$tables)
    {
        foreach ($tables as $table) {
            $this->query("UPDATE $table SET deleted_at = NOW() WHERE id = :id", [
                'id' => $id
            ]);
        }
    }
    public function Delete($table, $id)
    {
        #update deleted_at
        $this->query("UPDATE $table SET deleted_at = NOW() WHERE id = :id", [
            'id' => $id
        ]);
    }
    public function DeleteAll($table)
    {
        $this->query("UPDATE $table SET deleted_at = NOW()");
    }
    public function DeleteAllHard($table)
    {
        $this->query("DELETE FROM $table");
        $this->query("ALTER TABLE $table AUTO_INCREMENT = 1");
    }
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