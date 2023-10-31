<?php

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

#base path is the parent directory of this folder

require BASE_PATH . 'functions.php';

use DAO\MoviesDAO;


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
function get_collumns($filtered_data, $collumns){
    $data_array = [];
    foreach ($filtered_data as $row) {
        $row = array_merge(array_flip($collumns), $row);
        $data_array[] = $row;
    }
    return $data_array;
}

function update_movies($data_array, $updated_columns, $batch_size = 100) {
    $movies = new MoviesDAO();
    
    $num_rows = count($data_array);
    $num_batches = ceil($num_rows / $batch_size);
    
    for ($i = 0; $i < $num_batches; $i++) {
        $start = $i * $batch_size;
        $end = min(($i + 1) * $batch_size, $num_rows);
        $batch_data = array_slice($data_array, $start, $end - $start, true);
        
        foreach ($batch_data as $row) {
            $movies->update($row['id'], $row, [$updated_columns]);
        }
    }
}

$csvData = parseCSV(BASE_PATH . 'datasets/movies_metadata_cleaned.csv', ['id', 'poster_path']);
update_movies($csvData, 'poster_path');
echo "done";

