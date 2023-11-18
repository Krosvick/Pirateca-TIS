<?php

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

#base path is the parent directory of this folder

require BASE_PATH . 'functions.php';

use DAO\MoviesDAO;
use DAO\UsersDAO;
use DAO\RatingsDAO;
use Models\Rating;


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

//now comes the ratings csv part, we will read this csv wich contains the following columns
//user_id, movie_id, rating, timestamp
//we will only use the first 3 columns
//we will use the user_ids first to populate the users table and seed the remaining user columns with random data


function get_user_ids($ratings_file){
    $user_ids = [];
    foreach ($ratings_file as $row) {
        $user_ids[] = $row['userId'];
    }
    $user_ids = array_unique($user_ids);
    $user_ids = array_values($user_ids);
    return $user_ids;
}



//now we create a function that for every user_id in the array, it will create a fake data for the user
//users table has the following columns
//id, username, hashed_password, first_name, last_name, created_at, updated_at, deleted_at, role
//updated_at and deleted_at are nullable
//role will be set to 'user' for all users

function create_fake_user($user_id){
    $faker = Faker\Factory::create();
    $user = [
        'username' => $faker->userName,
        'hashed_password' => password_hash($faker->password, PASSWORD_DEFAULT),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'role' => 'user',
        'id' => $user_id
    ];
    return $user;
}

//now we will create a function that will create users and insert them into the users table
function create_users($user_ids){
    $users = new UsersDAO();
    $num_rows = count($user_ids);
    for ($i = 0; $i < $num_rows; $i++) {
        $user = create_fake_user($user_ids[$i]);
        try{
            $users->register($user);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

function create_ratings($ratings_file){
    $ratings = new RatingsDAO();
    $num_rows = count($ratings_file);
    for ($i = 0; $i < $num_rows; $i++) {
        $rating = $ratings_file[$i];
        try{
            $ratings->register($rating);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

function rename_collumns($ratings_file){
    $ratings_file = array_map(function($row){
        $row['user_id'] = $row['userId'];
        $row['movie_id'] = $row['movieId'];
        unset($row['userId']);
        unset($row['movieId']);
        return $row;
    }, $ratings_file);
    return $ratings_file;
}

$rating = new Rating();
$post = $rating->post_all_ratings();

