<?php
    require_once __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $dsn = "mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_NAME"]}";
    $options = array(
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/cacert.pem',
    );
    try {
        $pdo = new PDO($dsn, $_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"], $options);
        echo "Connected successfully";
    } catch (PDOException $error) {
        $msg = $error->getMessage();
        echo "An error occurred: $msg";
    }
?>