<?php

namespace Controllers;

use Models\Movie;
use Models\User;
use DAO\moviesDAO;

class MovieController {
    private $movieDAO;
    private $client;

    public function __construct() {
        $this->movieDAO = new moviesDAO();
        
    }

    public function listMovies() {
        $movies = $this->movieDAO->get_all();
        // Implement code to display a list of movies in the View
    }

    public function list_movies_for_user(User $user){
        $user_recommended_movies = $user->get_recommended_movies(10);
        return $user_recommended_movies;
    }

    public function showMovie($id) {
        $movie = $this->movieDAO->find($id);
        // Implement code to display the movie details in the View
    }

    public function createMovie($originalTitle, $overview, $genres, $belongsToCollection, $adult, $originalLanguage, $releaseDate) {
        $movie = new Movie(null, $originalTitle, $overview, $genres, $belongsToCollection, $adult, $originalLanguage, $releaseDate);
        $this->movieDAO->register($movie);
        // Implement code to handle the creation process
    }

    public function updateMovie($id, $originalTitle, $overview, $genres, $belongsToCollection, $adult, $originalLanguage, $releaseDate) {
        $movie = new Movie($id, $originalTitle, $overview, $genres, $belongsToCollection, $adult, $originalLanguage, $releaseDate);
        $this->movieDAO->update($id, $movie);
        // Implement code to handle the update process
    }

    public function deleteMovie($id) {
        $this->movieDAO->delete($id);
        // Implement code to handle the deletion process
    }
}
?>
