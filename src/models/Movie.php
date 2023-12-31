<?php

namespace Models;

use Core\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;


/**
 * Class Movie
 *
 * Represents a movie object with various properties such as ID, title, overview, poster path, genres, etc.
 * Provides getter and setter methods for accessing and modifying the properties.
 * Contains methods for finding movies, retrieving movie posters, retrieving movie directors, searching for movies, getting all movies, deleting a movie, and adding a movie.
 */
class Movie extends Model{

    private $id;
    private $original_title;
    private $overview;
    private $poster_path;
    private $genres;
    private $belongs_to_collection;
    private bool $adult;
    private $original_language;
    private $release_date;
    private $deleted_at;
    private $updated_at;
    private $director;
    private $poster_status;
    //ratings array
    private array $ratings;

  
    /**
     * Constructor method for the Movie class.
     *
     * Initializes the properties of the Movie object with the provided values.
     *
     * @param int|null $id The ID of the movie.
     * @param string|null $original_title The original title of the movie.
     * @param string|null $overview A brief overview of the movie.
     * @param string|null $poster_path The path to the movie's poster image.
     * @param array|null $genres An array of genres that the movie belongs to.
     * @param string|null $belongs_to_collection The name of the collection that the movie belongs to.
     * @param bool $adult Indicates if the movie is suitable for adults only.
     * @param string|null $original_language The original language of the movie.
     * @param string|null $release_date The release date of the movie.
     * @param string|null $deleted_at The date when the movie was deleted, or null if it is not deleted.
     * @param string|null $updated_at The date when the movie was last updated, or null if it is not updated.
     * @param string|null $director The name of the movie's director.
     * @param bool $poster_status Indicates if the movie's poster is available.
     * @return void
     */
    public function __construct($id = null, $original_title = null, $overview = null, $poster_path = null, $genres = null, $belongs_to_collection = null, $adult = false, $original_language = null, $release_date = null, $deleted_at = null, $updated_at = null, $director = null, $poster_status = false)
    {
        $this->id = $id;
        $this->original_title = $original_title;
        $this->overview = $overview;
        $this->poster_path = $poster_path;
        $this->genres = $genres;
        $this->belongs_to_collection = $belongs_to_collection;
        $this->adult = $adult == 1 ? true : false;
        $this->original_language = $original_language;
        $this->release_date = $release_date;
        $this->deleted_at = $deleted_at;
        $this->updated_at = $updated_at;
        $this->director = $director;
        $this->poster_status = $poster_status;
    }
    #getters and setters
    /**
     * Get the ID of the movie.
     *
     * @return int The ID of the movie.
     */
    public function get_id(){
        return $this->id;
    }

    /**
     * Set the ID of the movie.
     *
     * @param int $id The ID of the movie.
     * @return void
     */
    public function set_id($id){
        $this->id = $id;
    }

    /**
     * Get the original title of the movie.
     *
     * @return string The original title of the movie.
     */
    public function get_original_title(){
        return $this->original_title;
    }

    /**
     * Set the original title of the movie.
     *
     * @param string $title The original title of the movie.
     * @return void
     */
    public function set_original_title($title){
        $this->original_title = $title;
    }

    /**
     * Get the overview of the movie.
     *
     * @return string The overview of the movie.
     */
    public function get_overview(){
        return $this->overview;
    }

    /**
     * Set the overview of the movie.
     *
     * @param string $overview The overview of the movie.
     * @return void
     */
    public function set_overview($overview){
        $this->overview = $overview;
    }

    /**
     * Get the poster path of the movie.
     *
     * @return string The poster path of the movie.
     */
    public function get_poster_path(){
        return $this->poster_path;
    }

    /**
     * Set the poster path of the movie.
     *
     * @param string $poster_path The poster path of the movie.
     * @return void
     */
    public function set_poster_path($poster_path){
        $this->poster_path = $poster_path;
    }

    /**
     * Get the genres of the movie.
     *
     * @return array The genres of the movie.
     */
    public function get_genres(){
        return $this->genres;
    }

    /**
     * Set the genres of the movie.
     *
     * @param array $genres The genres of the movie.
     * @return void
     */
    public function set_genres($genres){
        $this->genres = $genres;
    }

    /**
     * Get the collection that the movie belongs to.
     *
     * @return string The collection that the movie belongs to.
     */
    public function get_belongs_to_collection(){
        return $this->belongs_to_collection;
    }

    /**
     * Set the collection that the movie belongs to.
     *
     * @param string $belongs_to_collection The collection that the movie belongs to.
     * @return void
     */
    public function set_belongs_to_collection($belongs_to_collection){
        $this->belongs_to_collection = $belongs_to_collection;
    }

    /**
     * Check if the movie is for adults only.
     *
     * @return bool True if the movie is for adults only, false otherwise.
     */
    public function get_adult(){
        return $this->adult;
    }

    /**
     * Set whether the movie is for adults only.
     *
     * @param bool $adult True if the movie is for adults only, false otherwise.
     * @return void
     */
    public function set_adult($adult){
        $this->adult = $adult;
    }

    /**
     * Get the original language of the movie.
     *
     * @return string The original language of the movie.
     */
    public function get_original_language(){
        return $this->original_language;
    }

    /**
     * Set the original language of the movie.
     *
     * @param string $original_language The original language of the movie.
     * @return void
     */
    public function set_original_language($original_language){
        $this->original_language = $original_language;
    }

    /**
     * Get the release date of the movie.
     *
     * @return string The release date of the movie.
     */
    public function get_release_date(){
        return $this->release_date;
    }

    /**
     * Set the release date of the movie.
     *
     * @param string $release_date The release date of the movie.
     * @return void
     */
    public function set_release_date($release_date){
        $this->release_date = $release_date;
    }

    /**
     * Get the deleted date of the movie.
     *
     * @return string The deleted date of the movie.
     */
    public function get_deleted_at(){
        return $this->deleted_at;
    }

    /**
     * Set the deleted date of the movie.
     *
     * @param string $deleted_at The deleted date of the movie.
     * @return void
     */
    public function set_deleted_at($deleted_at){
        $this->deleted_at = $deleted_at;
    }

    /**
     * Get the updated date of the movie.
     *
     * @return string The updated date of the movie.
     */
    public function get_updated_at(){
        return $this->updated_at;
    }

    /**
     * Set the updated date of the movie.
     *
     * @param string $updated_at The updated date of the movie.
     * @return void
     */
    public function set_updated_at($updated_at){
        $this->updated_at = $updated_at;
    }

    /**
     * Get the director of the movie.
     *
     * @return string The director of the movie.
     */
    public function get_director(){
        return $this->director;
    }

    /**
     * Set the director of the movie.
     *
     * @param string $director The director of the movie.
     * @return void
     */
    public function set_director($director){
        $this->director = $director;
    }
    /**
     * Retrieves the ratings of the movie.
     *
     * @return array The ratings of the movie.
     */
    public function get_ratings(){
        return $this->ratings;
    }
    /**
     * Sets the ratings for the movie.
     *
     * @param array $ratings The ratings to be set for the movie.
     * @return void
     */
    public function set_ratings($ratings){
        $this->ratings = $ratings;
    }
    /**
     * Returns the value of the `poster_status` property.
     *
     * @return mixed The value of the `poster_status` property.
     */
    public function get_poster_status(){
        return $this->poster_status;
    }
    /**
     * Sets the value of the `poster_status` property based on the input parameter.
     *
     * @param boolean $poster_status The value to set the `poster_status` property to. It can be either `1` or `0`.
     * @return void
     */
    public function set_poster_status($poster_status){
        if($poster_status == 1){
            $poster_status = true;
        }
        else{
            $poster_status = false;
        }
        $this->poster_status = $poster_status;
    }
    


    /**
     * Get the validation rules for the 'original_title' attribute.
     *
     * @return array The validation rules for the 'original_title' attribute.
     */
    public function rules(){
        return [
            'original_title' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 255]],
            'overview' => [self::RULE_REQUIRED,[self::RULE_MAX, 'max' => 1000]],
        ];
    }

    /**
     * Get all the attributes of the 'Movie' class.
     *
     * @return array All the attributes of the 'Movie' class.
     */
    public function attributes(){
        return [
            'original_title',
            'overview',
            'poster_path',
            'genres',
            'belongs_to_collection',
            'adult',
            'original_language',
            'release_date',
            'deleted_at',
            'updated_at',
            'director',
            'poster_status'
        ];
    }
    

    /**
     * Get the primary key of the movie.
     *
     * @return string The primary key of the movie.
     */
    public static function primaryKey()
    {
        return 'id';
    }
   

    #movie poster fallback is now called on a complete list
    /**
     * A method that checks the availability of movie posters and updates their status in a database.
     *
     * @param object $moviesDAO An instance of the MoviesDAO class that provides access to the movie database.
     * @param array $movies An array of movie objects representing the movies to check and update the poster status.
     * @return int The number of movie posters successfully updated.
     */
    public function moviePosterFallbackArray($moviesDAO, $movies)
    {
        $client = new Client();
        $promises = [];
        $successfulUpdates = 0;

        foreach ($movies as $movie) {
            $url = "https://image.tmdb.org/t/p/w780" . $movie->poster_path;
            $promises[] = $client->getAsync($url)->then(
                function ($response) use ($movie, $moviesDAO, &$successfulUpdates) {
                    if ($response->getStatusCode() == 200) {
                        $movie->poster_status = true;
                        $moviesDAO->update($movie->id, $movie, ['poster_status']);
                        $successfulUpdates++;
                    }
                },
                function ($exception) use ($movie, $moviesDAO, $client, &$successfulUpdates) {
                    if ($exception->getCode() == 404) {
                        $new_poster_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movie->id.'/images?language=en', [
                            'headers' => [
                                'Authorization' => 'Bearer '. $_ENV['TMDB_API_KEY'],
                                'accept' => 'application/json',
                            ]
                        ]);
                        $new_poster_response = json_decode($new_poster_request->getBody(), true);
                        if(count($new_poster_response['posters']) > 0){
                            $new_poster_url = $new_poster_response['posters'][0]['file_path'];
                            $movie->poster_path = $new_poster_url;
                            $movie->poster_status = true;
                            $moviesDAO->update($movie->id, $movie, ['poster_path']);
                            $moviesDAO->update($movie->id, $movie, ['poster_status']);
                            $successfulUpdates++;
                        }
                        else{
                            $movie->poster_path = 'https://www.movienewz.com/img/films/poster-holder.jpg';
                        }
                    }
                }
            );
        }

        // Wait for all the promises to complete
        foreach ($promises as $promise) {
            $promise->wait();
        }

        return $successfulUpdates;
    }

    /**
     * A method that checks if a movie's poster is available and updates its status accordingly.
     *
     * @param object $moviesDAO An instance of the MoviesDAO class used to update the movie's poster status and path.
     * @return string The current poster path or a default poster URL.
     */
    public function moviePosterFallback($moviesDAO)
    {
        $client = new Client();
        $moviePoster = $this->poster_path;
        $url = "https://image.tmdb.org/t/p/w780".$moviePoster;
        if($this->poster_status == true){
            return $moviePoster;
        }

        $promise = $client->getAsync($url)->then(
            function ($response) use ($moviesDAO, $moviePoster) {
                if($response->getStatusCode() == 200){
                    $this->poster_status = true;
                    $result = $moviesDAO->update($this->id, $this, ['poster_status']);
                }
                return $moviePoster;
            },
            function ($exception) use ($client, $moviesDAO) {
                if($exception->getCode() == 404){
                    $new_poster_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$this->id.'/images?language=en', [
                        'headers' => [
                            'Authorization' => 'Bearer '. $_ENV['TMDB_API_KEY'],
                            'accept' => 'application/json',
                        ]
                    ]);
                    $new_poster_response = json_decode($new_poster_request->getBody(), true);
                    if(count($new_poster_response['posters']) > 0){
                        $new_poster_url = $new_poster_response['posters'][0]['file_path'];
                        $this->poster_path = $new_poster_url;
                        $this->poster_status = true;
                        $result = $moviesDAO->update($this->id, $this, ['poster_path']);
                        $result = $moviesDAO->update($this->id, $this, ['poster_status']);
                    }
                    else{
                        return 'https://www.movienewz.com/img/films/poster-holder.jpg';
                    }
                }
            }
        );

        // Wait for the promise to complete
        $promise->wait();

        return $moviePoster;
    }


    /**
     * @param array $movie a movie array
     * 
     * @return string return a movie director
     */

    public function MovieDirectorRetrieval($moviesDAO){
        $client = new Client();
        if($this->director != null){
            return $this->director;
        }
        $movie_credits_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$this->id.'/credits?language=en-US', [
            'headers' => [
                'Authorization' => 'Bearer '. $_ENV['TMDB_API_KEY'],
                'accept' => 'application/json',
            ]
        ]);
        $movie_credits_response = json_decode($movie_credits_request->getBody(), true);
        if(count($movie_credits_response['crew']) == 0){
            return null;
        }
        $movie_director = $movie_credits_response['crew'][0]['name'];
        $this->director = $movie_director;
        $moviesDAO->update($this->id, $this, ['director']);
        return $movie_director;
    }


    /**
     * @param string $title a movie title
     * 
     * @return array<array> return a list of movies based on tittle
     */

    public function search_movie($title, $moviesDAO){
        $movies = $moviesDAO->search($title); //need search function in moviesDAO or something similar
        return $movies;
    }
}