<?php
    const BASE_PATH = __DIR__.'/';
    require 'functions.php';
    require 'Database.php';
    require_once __DIR__ . '/vendor/autoload.php';
    #needed rows
    $needed_columns = ['title','adult','genres','original_language', 'original_title','overview','release_date'];

    $csv = new \ParseCsv\Csv();
    $csv->delimiter = ",";
    $csv->auto(base_path("/datasets/movies_metadata_cleaned.csv"));

    $filteredData = [];

    foreach ($csv->data as $row) {
        $filteredRow = array_intersect_key($row, array_flip($needed_columns));
        $filteredData[] = $filteredRow;
    }

    #we are going to inser into the following tables all the data
    #table:
    /*CREATE TABLE `movies` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`original_title` varchar(255),
	`overview` text,
	`genres` varchar(255),
	`belongs_to_collection` json,
	`adult` tinyint(1),
	`original_language` varchar(3),
	`release_date` date,
	PRIMARY KEY (`id`)
    )ENGINE InnoDB,
    CHARSET utf8mb4,
    COLLATE utf8mb4_0900_ai_ci;
  */
   
    $movies = [];
    foreach ($filteredData as $row) {
        $movies[] = [
            'original_title' => $row['original_title'],
            'overview' => $row['overview'],
            'genres' => $row['genres'],
            'belongs_to_collection' => $row['belongs_to_collection'],
            'adult' => $row['adult'],
            'original_language' => $row['original_language'],
            'release_date' => $row['release_date'],
        ];
    }

    $db = new Database();
    foreach ($movies as $movie) {
        $db->query("INSERT INTO movies (original_title, overview, genres, belongs_to_collection, adult, original_language, release_date) VALUES (:original_title, :overview, :genres, :belongs_to_collection, :adult, :original_language, :release_date)", $movie);
    }







