<?php

namespace Core;
use PDO;

class Database
{
    private static $instance;
    private $connection;
    public $statement; 

    private function __construct($username = 'root', $password = '') {
        $dsn = "mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_NAME"]}";
        $options = array(
            PDO::MYSQL_ATTR_SSL_CA => base_path('/cacert.pem'),
        );

        $this->connection = new PDO($dsn, $username, $password, $options);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database($_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"]);
        }
        return self::$instance;
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    public function get($table, $batchSize = NULL)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM $table");
        $this->statement->execute();
        $results = $this->statement->fetchAll();

        if ($batchSize) {
            $results = array_chunk($results, $batchSize);
        }

        return $results;
    }

    public function find()
    {
        return $this->statement->fetch();
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