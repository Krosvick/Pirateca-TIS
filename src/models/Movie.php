<?php

namespace Models;

use DAO\moviesDAO;
use GuzzleHttp\Client;


class Movie{

    private MoviesDAO $moviesDAO;
    public $movies_list;

    public function __construct(){
        $this->moviesDAO = new MoviesDAO();
        $this->movies_list = $this->moviesDAO->get_some();
    }

    public function find_movies($id_list){ //id_list is an array of arrays, check the example in the model user and index controller
        $movies = array();
        foreach($id_list as $body){
            foreach($body as $movie){
                $movie = $this->find_movie($movie['id']);
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
        $movie = $this->moviesDAO->find($id);
        if ($movie != null){
            $movie['poster_path'] = $this->moviePosterFallback($movie);
            $movie['director'] = $this->MovieDirectorRetrieval($movie);
        }
        return $movie;
    }


    /**
     * 
     * @param array $movie  a movie data array
     * 
     * @return string a movie poster url
     */


    public function moviePosterFallback($movie){
        $client = new Client();
        $moviePoster = $movie['poster_path'];
        try{
            $response = $client->request('GET', 'https://image.tmdb.org/t/p/original'.$movie['poster_path']);
            return $movie['poster_path'];
        }catch(\Exception $e){
            if($e->getCode() == 404){
                $new_poster_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movie['id'].'/images?language=en', [
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
                    $this->moviesDAO->update($movie['id'], $movie, ['poster_path']);
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

    public function MovieDirectorRetrieval($movie){
        $client = new Client();
        $movie_credits_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movie['id'].'/credits?language=en-US', [
            'headers' => [
                'Authorization' => 'Bearer '. $_ENV['TMDB_API_KEY'],
                'accept' => 'application/json',
            ]
        ]);
        $movie_credits_response = json_decode($movie_credits_request->getBody(), true);
        $movie_director = $movie_credits_response['crew'][0]['name'];
        $this->moviesDAO->update($movie['id'], $movie, ['director']);
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