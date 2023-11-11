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

    public function find_movies($id_list){
        $movies = array();
        foreach($id_list as $body){
            foreach($body as $movie){
                $movie = $moviesDAO->find($movie['movieId']);
                if ($movie != null){
                    array_push($movies, $movie);
                }
            }
        }
        return $movies;
    }
    
}
?>