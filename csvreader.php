<?php
    const BASE_PATH = __DIR__.'/';
    require 'functions.php';
    require_once __DIR__ . '/vendor/autoload.php';
    #needed rows
    $needed_columns = ['title','adult','genres','original_language', 'original_title','overview','release_date'];

    $csv = new \ParseCsv\Csv();
    $csv->delimiter = ",";
    $csv->auto(base_path("/datasets/movies_metadata.csv"));

    $filteredData = [];

    foreach ($csv->data as $row) {
        $filteredRow = array_intersect_key($row, array_flip($needed_columns));
        $filteredData[] = $filteredRow;
    }

    $jsonData = json_encode($filteredData, JSON_PRETTY_PRINT);

    // Specify the file path in the current directory
    $file_path = base_path('/datasets/json_files/filtered_data.json');

    // Save the JSON data to the file
    file_put_contents($file_path, $jsonData);

    echo "JSON data saved to: $file_path";






