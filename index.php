<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
const BASE_PATH = __DIR__.'/';

require 'functions.php';
//require 'router.php';
require 'Database.php';

$db = new Database($_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
dd($db);

