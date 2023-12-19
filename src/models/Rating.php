<?php

namespace Models;

use Core\Model;
use Models\User;
use Models\Movie;
use DAO\RatingsDAO;
use GuzzleHttp\Client;

class Rating extends Model{

    private $id;
    private ?User $user;
    private ?Movie $movie;
    private $rating;
    private $review;

   
    /**
     * Rating constructor.
     *
     * Initializes the properties of the Rating object with the provided values.
     *
     * @param int|null $id The ID of the rating.
     * @param User|null $user The user associated with the rating.
     * @param Movie|null $movie The movie associated with the rating.
     * @param float|null $rating The rating value.
     * @param string|null $review The review comment.
     */
    public function __construct($id = null, $user = null, $movie = null, $rating = null, $review = null)
    {
        $this->id = $id;
        $this->user = $user;
        $this->movie = $movie;
        $this->rating = $rating;
        $this->review = $review;
    }
    

 
    /**
     * Get the ID of the rating.
     *
     * @return int The ID of the rating.
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Set the ID of the rating.
     *
     * @param int $id The ID of the rating.
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * Get the user associated with the rating.
     *
     * @return string The user associated with the rating.
     */
    public function get_user()
    {
        return $this->user;
    }

    /**
     * Set the user associated with the rating.
     *
     * @param string $user The user associated with the rating.
     */
    public function set_user($user)
    {
        $this->user = $user;
    }

    /**
     * Get the movie associated with the rating.
     *
     * @return string The movie associated with the rating.
     */
    public function get_movie()
    {
        return $this->movie;
    }

    /**
     * Set the movie associated with the rating.
     *
     * @param string $movie The movie associated with the rating.
     */
    public function set_movie($movie)
    {
        $this->movie = $movie;
    }

    /**
     * Get the rating value.
     *
     * @return float The rating value.
     */
    public function get_rating()
    {
        return $this->rating;
    }

    /**
     * Set the rating value.
     *
     * @param float $rating The rating value.
     */
    public function set_rating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * Get the review comment.
     *
     * @return string The review comment.
     */
    public function get_review()
    {
        return $this->review;
    }

    /**
     * Set the review comment.
     *
     * @param string $review The review comment.
     */
    public function set_review($review)
    {
        $this->review = $review;
    }

    /**
     * Get the primary key of the Rating model.
     *
     * @return string The primary key of the Rating model.
     */
    public static function primaryKey()
    {
        return 'id';
    }

    /**
     * Search for ratings by movie ID.
     *
     * @param int $movie_id The ID of the movie to search for ratings.
     * @return array An array of ratings for the specified movie.
     */
    public function search_by_movie_id($movie_id)
    {
        $ratings = $this->ratingsDAO->get_by_movie($movie_id);
        return $ratings;
    }

    /**
     * Add a new rating.
     *
     * @param int $user_id The ID of the user associated with the rating.
     * @param float $rating The rating value.
     * @param int $movie_id The ID of the movie associated with the rating.
     * @param string $commentary The commentary for the rating.
     */
    private function add_rating($user_id, $rating, $movie_id, $commentary)
    {
        //add timestamp function here
        $rating = new Rating($user_id, $rating, $movie_id, $commentary);
        $this->ratingsDAO->add($rating);
    }

    /**
     * Delete a rating.
     *
     * @param int $user_id The ID of the user associated with the rating.
     * @param int $movie_id The ID of the movie associated with the rating.
     */
    private function delete_review($user_id, $movie_id)
    {
        $this->ratingsDAO->delete($user_id, $movie_id);
    }

    /**
     * Show ratings for a specific movie.
     *
     * @param int $movie_id The ID of the movie to show ratings for.
     * @return array An array of ratings for the specified movie.
     */
    private function show_movie_ratings($movie_id)
    {
        $ratings = $this->ratingsDAO->get_by_movie($movie_id);
        return $ratings;
    }

    /**
     * Get all ratings.
     *
     * @return array An array of all ratings.
     */
    private function get_all()
    {
        $ratings = $this->ratingsDAO->get_all();
        return $ratings;
    }

   
    /**
     * Sends a POST request to a specific URL with the ratings data.
     *
     * @return array The response from the POST request, decoded as an associative array.
     */
    public function post_all_ratings()
    {
        $client = new Client();
        $ratings = $this->get_all();
        $response = $client->request('POST', 'localhost:8001/ratings', [
            'json' => $ratings
        ]);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    
}