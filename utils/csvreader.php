<?php
    const BASE_PATH = __DIR__.'/../';
    require BASE_PATH.'functions.php';
    require base_path('/core/Database.php');
    require base_path('/dao/moviesdao.php');
    require_once base_path('/vendor/autoload.php');

    use DAO\moviesDAO;
    use Core\Database;

    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
    #needed rows
    $needed_columns_movies = ['id','original_title','adult','genres','original_language','overview','release_date','belongs_to_collection'];
    $needed_columns_users = [];

    $movies_values = ':id, :original_title, :adult, :genres, :original_language, :overview, :release_date, :belongs_to_collection';


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
        $error_rows = [];
        foreach ($data as $row) {
            #every row is an array containing the values
            #ex: ['id' => 1, 'title' => 'movie title', ...]
            #so we need to implode the values to make it a string
            try{
                implode(', ', $row);
                $db->query("INSERT INTO $table ($query_parameters) VALUES ($values)", $row);
            }catch(Exception $e){
                echo $e->getMessage();
                echo '<pre>';
                print_r($row);
                echo '</pre>';
                echo '<br>';
                //append row into the error_rows array
                $error_rows[] = $row;
            }
            //save the error rows into a json file
            file_put_contents(BASE_PATH.'/datasets/json_files/error_rows.json', json_encode($error_rows));
        }
    }
    
    function dataToArray($filtered_data, $needed_columns, $keys){
        $data_array = [];
        foreach ($filtered_data as $row) {
            $row = array_merge(array_flip($needed_columns), $row);
            #then assign the values to the keys
            $row = array_combine($keys, $row);
            $data_array[] = $row;
        }
        $data_array = jsonEncode($data_array, 'genres', 'belongs_to_collection');
        $data_array = numToBoolean($data_array, 'adult');
        return $data_array;
        
    }
    
    function jsonEncode($data, ...$columns){
        $result = [];
        foreach ($data as $row) {
            foreach ($columns as $column) {
                $row[$column] = json_encode($row[$column]);
            }
            $result[] = $row;
        }
        return $result;
    }

    function numToBoolean($data, $column){
        $result = [];
        foreach ($data as $row) {
            $row[$column] = $row[$column] === 'True' ? 1 : 0;
            $result[] = $row;
        }
        return $result;
    }

$peliculas = new moviesDAO('movies');
$peliculas_result = $peliculas->get_some(10, 0);
print_r($peliculas_result);


    







