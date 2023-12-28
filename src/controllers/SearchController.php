<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use GuzzleHttp\Client;
use Exception;


/**
 * Class SearchController
 *
 * The SearchController class is responsible for handling search functionality.
 */
class SearchController extends BaseController
{
    private $movied;
    private $moviem;
    
    /**
     * SearchController constructor.
     *
     * Initializes a new instance of the SearchController class.
     *
     * @param string $base_url The base URL for the controller.
     * @param array $routeParams The route parameters containing the search query and page number.
     */
    public function __construct($base_url, $routeParams) {
        parent::__construct(...func_get_args());
        $this->movied = new moviesDAO();
        $this->moviem = new Movie();
    }

 
    /**
     * Search for movies based on a search query and page number.
     *
     * @param string $busqueda The search query.
     * @param int|null $page The page number. Defaults to 1 if not provided.
     * @return string The rendered view with a list of movies that match the search query.
     */
    public function search($busqueda = '', $page = null){
        if($busqueda == '') {
            $busqueda = $this->routeParams['busqueda'];
        }
        if($page == null || $this->routeParams['page'] == null) {
            $page = 1;
        } else {
            $page = $this->routeParams['page'];
        }
        //sanitize the url and fix encoding like %20 for spaces in $busqueda
        $busqueda = urldecode($busqueda);
        htmlspecialchars($busqueda);

        $movies_data = $this->movied->fulltext_search($busqueda,$page);
        $totalPages = $movies_data['totalPages'];
       
        $movies = [];
        foreach($movies_data["rows"] as $movie_data){
            try{
                $movie = new Movie();
                $movie->set_id($movie_data->id);
                $movie->set_original_title($movie_data->original_title);
                $movie->set_overview($movie_data->overview);
                $movie->set_poster_path($movie_data->poster_path);
                $movie->set_genres($movie_data->genres);
                $movie->set_belongs_to_collection($movie_data->belongs_to_collection);
                $movie->set_adult($movie_data->adult);
                $movie->set_original_language($movie_data->original_language);
                $movie->set_release_date($movie_data->release_date);
                $movie->set_director($movie_data->director);
                $movie->set_poster_status($movie_data->poster_status);
                $movie->set_poster_path($movie->moviePosterFallback());
                $movie->set_director($movie->MovieDirectorRetrieval());
                array_push($movies, $movie);
            }
            catch (Exception $e) {
                echo ("hola");
                continue;
            }
           
        }
        
        if(!$movies){
            $this->response->abort(404);
        }
        $data = [
            'movies' => $movies,
            'busqueda' =>$busqueda,
            'page' => $page,
            'totalPages' => $totalPages,
            'firstId' => $movies_data['firstId'],
            'lastId' => $movies_data['lastId'],
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.', 
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata,
            'cssFiles' =>  ['styles-movie.css']
        ];

        return $this->render("test",$optionals);
    }
        
}
