<?php
    const BASE_PATH = __DIR__.'..';
    require 'functions.php';
    require 'Database.php';
    require_once __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    #needed rows
    $needed_columns_movies = ['title','adult','genres','original_language', 'original_title','overview','release_date','belongs_to_collection'];
    $needed_columns_users = [];

    $csv = new \ParseCsv\Csv();
    $csv->delimiter = ",";
    $csv->auto(base_path("/datasets/movies_metadata_cleaned.csv"));

    $filteredData = [];

    foreach ($csv->data as $row) {
        $filteredRow = array_intersect_key($row, array_flip($needed_columns));
        $filteredData[] = $filteredRow;
    }
    $movies = [];
    foreach ($filteredData as $row) {
        $movies[] = [
            'original_title' => $row['original_title'],
            'overview' => $row['overview'],
            'genres' => json_encode($row['genres']),
            'belongs_to_collection' => json_encode($row['belongs_to_collection']),
            'adult' => $row['adult'] == 'True' ? 1 : 0,
            'original_language' => $row['original_language'],
            'release_date' => $row['release_date'],
        ];
    }

    $db = new Database($_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    #split the array into chunks of 1000
    $movies = array_chunk($movies, 1000);
    #insert each chunk into the database
    set_time_limit(0);
    foreach ($movies as $chunk) {
        foreach ($chunk as $movie) {
            try {
                $db->query("INSERT INTO movies (original_title, overview, genres, belongs_to_collection, adult, original_language, release_date) VALUES (:original_title, :overview, :genres, :belongs_to_collection, :adult, :original_language, :release_date)", $movie);
            }
            catch (Exception $e) {
                echo $e->getMessage();
                echo '<br>';
                echo '<pre>';
                print_r($movie);
                echo '</pre>';
                continue;
            }
        }
    }







