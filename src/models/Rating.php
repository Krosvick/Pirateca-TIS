<?php

namespace Models;

use Core\Model;
use Models\User;
use Models\Movie;
use DAO\RatingsDAO;
use GuzzleHttp\Client;

/**
 * Rating class represents a rating given by a user to a movie.
 * It has properties such as id, user, movie, rating, review, and created_at.
 * Getter and setter methods are provided for these properties.
 * The class also has methods for searching ratings by movie ID, adding a new rating,
 * deleting a rating, showing ratings for a specific movie, getting all ratings,
 * and posting all ratings to a specific URL.
 */
class Rating extends Model{

    private $id;
    private ?User $user;
    private ?Movie $movie;
    private $rating;
    private $review;
    private $created_at;

   
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
    public function __construct($id = null, $user = null, $movie = null, $rating = null, $review = null, $created_at = null)
    {
        $this->id = $id;
        $this->user = $user;
        $this->movie = $movie;
        $this->rating = $rating;
        $this->review = $review;
        $this->created_at = $created_at;
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
     * Get the created_at value.
     *
     * @return string The created_at value.
     */
    public function get_created_at()
    {
        return $this->created_at;
    }
    /**
     * Set the created_at value.
     *
     * @param string $created_at The created_at value.
        */
    public function set_created_at($created_at)
    {
        $this->created_at = $created_at;
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
     * Get all ratings.
     *
     * @return array An array of all ratings.
     */
    private function get_all($ratingsDAO)
    {
        $ratings = $ratingsDAO->get_all();
        return $ratings;
    }

   
    /**
     * Sends a POST request to a specific URL with the ratings data.
     *
     * @return array The response from the POST request, decoded as an associative array.
     */
    public function post_all_ratings($ratingsDAO)
    {
        $client = new Client();
        $ratings = $this->get_all($ratingsDAO);
        $response = $client->request('POST', 'localhost:8001/ratings', [
            'json' => $ratings
        ]);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    
}