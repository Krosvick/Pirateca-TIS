<?php

use PDO;

class Database
{
    public $connection;
    public $statement;

    public function __construct($username = 'root', $password = '')
    {
        $dsn = "mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_NAME"]}";
        $options = array(
            PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/cacert.pem',
        );        

        $this->connection = new PDO($dsn, $username, $password, $options);
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    public function get()
    {
        return $this->statement->fetchAll();
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
}