<?php

namespace Models;

use DAO\moviesDAO;
use GuzzleHttp\Client;


class Movie{

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
    private MoviesDAO $moviesDAO;
    public $movies_list;

    public function __construct($id = null, $original_title = null, $overview = null, $poster_path = null, $genres = null, $belongs_to_collection = null, $adult = false, $original_language = null, $release_date = null, $deleted_at = null, $updated_at = null, $director = null)
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
        $this->moviesDAO = new MoviesDAO();
    }

    #getters and setters
    public function get_id(){
        return $this->id;
    }
    public function set_id($id){
        $this->id = $id;
    }
    public function get_title(){
        return $this->original_title;
    }
    public function set_title($title){
        $this->title = $title;
    }
    public function get_overview(){
        return $this->overview;
    }
    public function set_overview($overview){
        $this->overview = $overview;
    }
    public function get_poster_path(){
        return $this->poster_path;
    }
    public function set_poster_path($poster_path){
        $this->poster_path = $poster_path;
    }
    public function get_genres(){
        return $this->genres;
    }
    public function set_genres($genres){
        $this->genres = $genres;
    }
    public function get_belongs_to_collection(){
        return $this->belongs_to_collection;
    }
    public function set_belongs_to_collection($belongs_to_collection){
        $this->belongs_to_collection = $belongs_to_collection;
    }
    public function get_adult(){
        return $this->adult;
    }
    public function set_adult($adult){
        $this->adult = $adult;
    }
    public function get_original_language(){
        return $this->original_language;
    }
    public function set_original_language($original_language){
        $this->original_language = $original_language;
    }
    public function get_release_date(){
        return $this->release_date;
    }
    public function set_release_date($release_date){
        $this->release_date = $release_date;
    }
    public function get_deleted_at(){
        return $this->deleted_at;
    }
    public function set_deleted_at($deleted_at){
        $this->deleted_at = $deleted_at;
    }
    public function get_updated_at(){
        return $this->updated_at;
    }
    public function set_updated_at($updated_at){
        $this->updated_at = $updated_at;
    }
    public function get_director(){
        return $this->director;
    }
    public function set_director($director){
        $this->director = $director;
    }

    public function find_movies($id_list){ //id_list is an array of arrays, check the example in the model user and index controller
        $movies = array();
        foreach($id_list as $body){
            foreach($body as $movie){
                $movie = $this->find_movie($movie['movieId']);
                if ($movie != null){
                    array_push($movies, $movie);
                }
            }
        }
        $this->movies_list = $movies;
        return $this->movies_list;
    }

    /**
     * @param $id  a movie id
     * 
     * @return array<array>   an especific movie data array
     */

    public function find_movie($id){
        #second parameter of find is the class
        $movie = $this->moviesDAO->find($id, 'Models\Movie');
        return $movie;
    }


    // THIS SHIT IS TO SLOW
    public function search($busqueda){
        $movies = $this->moviesDAO->dummytest_fulltext($busqueda);
        foreach($movies as $key => $movie){
            if ($movie != null){
            $movies[$key]['poster_path'] = $this->moviePosterFallback($movie);
        }
        }
        return $movies; 
    }

    /**
     * 
     * @param array $movie  a movie data array
     * 
     * @return string a movie poster url
     */

    #movie poster fallback is called on self
    public function moviePosterFallback()
    {
        $client = new Client();
        $moviePoster = $this->poster_path;
        try{
            $response = $client->request('GET', 'https://image.tmdb.org/t/p/original'.$moviePoster);
            return $moviePoster;
        }catch(\Exception $e){
            if($e->getCode() == 404){
                $new_poster_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$this->id.'/images?language=en', [
                    'headers' => [
                        'Authorization' => 'Bearer '. $_ENV['TMDB_API_KEY'],
                        'accept' => 'application/json',
                    ]
                ]);
                $new_poster_response = json_decode($new_poster_request->getBody(), true);
                if(count($new_poster_response['posters']) > 0){
                    $new_poster_url = $new_poster_response['posters'][0]['file_path'];
                    $moviePoster = $new_poster_url;
                    #update the movie poster path in the database
                    $this->moviesDAO->update($this->id, $this, ['poster_path']);
                }
                else{
                    #if the movie doesn't have a poster, we will use a default image
                    $moviePoster = 'https://www.movienewz.com/img/films/poster-holder.jpg';
                }
            }
        }
        return $moviePoster;
    }


    /**
     * @param array $movie a movie array
     * 
     * @return string return a movie director
     */

    public function MovieDirectorRetrieval(){
        $client = new Client();
        $movie_credits_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$this->id.'/credits?language=en-US', [
            'headers' => [
                'Authorization' => 'Bearer '. $_ENV['TMDB_API_KEY'],
                'accept' => 'application/json',
            ]
        ]);
        $movie_credits_response = json_decode($movie_credits_request->getBody(), true);
        $movie_director = $movie_credits_response['crew'][0]['name'];
        $this->director = $movie_director;
        $this->moviesDAO->update($this->id, $this, ['director']);
        return $movie_director;
    }


    /**
     * @param string $title a movie title
     * 
     * @return array<array> return a list of movies based on tittle
     */

    public function search_movie($title){
        $movies = $this->moviesDAO->search($title); //need search function in moviesDAO or something similar
        return $movies;
    }

    /**
     * @return array<array> retrieve all movies
     */

    public function get_all(){
        $movies = $this->moviesDAO->get_all();
        return $movies;
    }

    /**
     * @param int $id a movie id
     * 
     * @return void
     */

    public function delete_movie($id){
        $this->moviesDAO->delete($id);
    }

    /**
     * @param array $movie a movie data array
     * 
     * @return void
     */

    public function add_movie($movie){
        $this->moviesDAO->add($movie);
    }

}
?>