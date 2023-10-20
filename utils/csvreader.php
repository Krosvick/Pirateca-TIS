<?php
    const BASE_PATH = __DIR__.'/..';
    require 'functions.php';
    require 'Database.php';
    require_once BASE_PATH . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
    #needed rows
    $needed_columns_movies = ['id','title','adult','genres','original_language', 'original_title','overview','release_date','belongs_to_collection'];
    $needed_columns_users = [];

    $movies_values = ':id, :title, :adult, :genres, :original_language, :original_title, :overview, :release_date, :belongs_to_collection';


    function parseCSV($file, $needed_columns) {
        $csv = new \ParseCsv\Csv();
        $csv->delimiter = ",";
        $csv->auto($file);
        $filteredData = [];
        foreach ($csv->data as $row) {
            $filteredRow = array_intersect_key($row, array_flip($needed_columns));
            $filteredData[] = $filteredRow;
        }
        return $filteredData;
    }

    function dataToDB($data, $table, $query_parameters, $values){
        set_time_limit(0);
        $db = new Database($_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
        foreach ($data as $row) {
            try {
                $db->query("INSERT INTO $table ($query_parameters) VALUES ($values)", $row);
            }
            catch (Exception $e) {
                echo $e->getMessage();
                echo '<br>';
                echo '<pre>';
                print_r($row);
                echo '</pre>';
                continue;
            }
        }
    }
    
    function dataToArray($filtered_data, $needed_columns, $keys){
        $data_array = [];
        foreach ($filteredData as $row) {
            $filteredRow = array_intersect_key($row, array_flip($needed_columns));
            $data_array[] = array_combine($keys, $filteredRow);
        }
        return $data_array;

    }

    $db = new Database($_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $db->Delete_All('movies');
    $movies = parseCSV(BASE_PATH.'/data/movies_metadata.csv', $needed_columns_movies);
    $movies = dataToArray($movies, $needed_columns_movies, $needed_columns_movies);
    dataToDB($movies, 'movies', implode(', ', $needed_columns_movies), $movies_values);

    







