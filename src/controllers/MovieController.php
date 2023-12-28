<?php

namespace Controllers;

use Core\Application;
use Models\Movie;
use DAO\moviesDAO;
use Core\BaseController;
use Core\Middleware\AdminMiddleware;

class MovieController extends BaseController {
    private $movieDAO;
    private $client;
    private $movieModel;

    public function __construct($base_url, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->movieDAO = new moviesDAO();
        $this->movieModel = new Movie();
        $this->registerMiddleware(new AdminMiddleware(['createMovie']));
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
    public function createMovie(){
        if($this->request->isPost()){
            // logic of request: Auth -> Validate -> Sanitize -> Save
            $body = (object) $this->request->getBody();
            $body->poster_status = 1 ? $body->poster_status = "1" : $body->poster_status = 0;
            $body->adult = 1 ? $body->adult = "1" : $body->adult = 0;
            $movie = new Movie();
            $movie->loadData($body);
            if(!$movie->validate()){
                $this->response->abort(404, "Movie data is not valid");
            }
            try {
            $stmt = $this->movieDAO->register($movie);
            $stmt = $stmt->get();
            } catch (\Throwable $th) {
                $this->response->abort(404, "Movie already exists");
            }
            Application::$app->session->setFlash('success', 'The movie was created successfully');
            $this->response->redirect('/');
        }
        $data = [
            'Movie' => $this->movieModel,
        ];
        $metadata = [
            'title' => 'Create Movie',
            'description' => 'This is the create movie page.', 
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata,
        ];
        $this->render('createMovie', $optionals);
    }
}
?>
