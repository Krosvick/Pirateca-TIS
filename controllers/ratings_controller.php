<?php

namespace controllers;

use models\user;
use models\movie;
use models\users;
use dao\ratingsdao;

class RatingsController {
    private $ratingsDAO;

    public function __construct() {
        $this->ratingsDAO = new RatingsDAO();
    }

    public function addRating(User $user, Movie $movie, $rating, $timestamp) {
        // You can add input validation and error handling here
        // Ensure that the rating is within the range of 1 to 5

        // Create a new UserRating object
        $userRating = new UserRating($user, $movie, $rating, $timestamp);

        // Register the user rating using the RatingsDAO
        $this->ratingsDAO->register($userRating);

        // Implement code to handle the rating addition process, e.g., redirect to the movie's details page
    }

    public function updateRating(User $user, Movie $movie, $rating, $timestamp) {
        // You can add input validation and error handling here
        // Ensure that the rating is within the range of 1 to 5

        // Retrieve the existing user rating from the database
        $existingRating = $this->ratingsDAO->find_by_user_id_and_movie_id($user->getUserId(), $movie->getMovieId());

        if ($existingRating) {
            // Update the user rating
            $existingRating->rating = $rating;
            $existingRating->timestamp = $timestamp;

            // Update the rating using the RatingsDAO
            $this->ratingsDAO->update($existingRating);

            // Implement code to handle the rating update process, e.g., redirect to the movie's details page
        } else {
            // User rating not found, you can handle this scenario as needed
            // Implement code to handle the update failure, e.g., display an error message
        }
    }

    public function getUserRatings(User $user) {
        // Retrieve all user ratings for a specific user using the RatingsDAO
        $userRatings = $this->ratingsDAO->find_by_user_id($user->getUserId());

        // Implement code to display or process the user ratings, e.g., show them in a user profile page
    }

    public function getMovieRatings(Movie $movie) {
        // Retrieve all ratings for a specific movie using the RatingsDAO
        $movieRatings = $this->ratingsDAO->find_by_movie_id($movie->getMovieId());

        // Implement code to display or process the movie ratings, e.g., show them on the movie's details page
    }
}
