<?php

namespace Models;
use DAO\moviesDAO;


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
                $movie = $this->moviesDAO->find($movie['movieId']);
                if ($movie != null){
                    array_push($movies, $movie);
                }
            }
        }
        $this->movies_list = $movies;
        return $this->movies_list;
    }

    public function find_movie($id){
        $movie = $this->moviesDAO->find($id);
        return $movie;
    }

    public function search_movie($title){
        $movies = $this->moviesDAO->search($title);
        return $movies;
    }

    public function get_all(){
        $movies = $this->moviesDAO->get_all();
        return $movies;
    }



    //search movie by title
    //show movie details
    //delete movie
    //add movie
    //view all movies


}
?>