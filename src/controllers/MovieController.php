<?php

namespace Controllers;

use Models\Movie;
use Models\User;
use DAO\moviesDAO;
use Core\BaseController;

class MovieController extends BaseController {
    private $movieDAO; //THIS SHOULDNT BE HERE IN THE FUTURE
    private $client;
    private $movieModel;

    public function __construct($base_url, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->movieDAO = new moviesDAO();
        $this->movieModel = new Movie();
    }

    /**
     * Retrieves all movies from the moviesDAO and displays a list of movies in the View.
     *
     * @return void
     */
    public function listMovies() {
        $movies = $this->movieDAO->get_all();
        // Implement code to display a list of movies in the View
    }

    /**
     * Retrieves a list of recommended movies for a given user.
     *
     * @param User $user The user object for which to retrieve recommended movies.
     * @return array The list of recommended movies for the user.
     */
    public function list_movies_for_user(User $user){
        $user_recommended_movies = $user->get_recommended_movies(10);
        return $user_recommended_movies;
    }

    /**
     * Creates a new Movie object with the provided parameters, registers it using the movieDAO, and performs additional actions to handle the creation process.
     *
     * @param string $originalTitle The original title of the movie.
     * @param string $overview A brief overview of the movie.
     * @param array $genres An array of genres that the movie belongs to.
     * @param string $belongsToCollection The collection that the movie belongs to.
     * @param bool $adult Indicates if the movie is for adults only.
     * @param string $originalLanguage The original language of the movie.
     * @param string $releaseDate The release date of the movie.
     * @return void
     */
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
