<?php

namespace Controllers;

use Core\Application;
use Models\Movie;
use Models\Comment;
use DAO\moviesDAO;
use Core\BaseController;
use Core\Middleware\AdminMiddleware;

/**
 * MovieController class
 *
 * Handles the creation of a new movie by validating the request data, saving it to the database, and redirecting the user to the home page.
 *
 */
class MovieController extends BaseController {
    private $movieDAO;
    private $movieModel;

    /**
     * MovieController constructor
     *
     * Initializes the movieDAO and movieModel properties and registers the AdminMiddleware middleware for the createMovie action.
     *
     * @param string $base_url The base URL of the application.
     * @param array $routeParams The route parameters passed to the controller.
     */
    public function __construct($base_url, $routeParams) {
        parent::__construct(...func_get_args());
        $this->movieDAO = new moviesDAO();
        $this->movieModel = new Movie();
        $this->registerMiddleware(new AdminMiddleware(['createMovie']));
    }

    /**
     * Create a new movie
     *
     * If the request method is POST, validate the request data, save the movie to the database, set a success flash message in the session, and redirect the user to the home page.
     * If the request method is not POST, render the createMovie view with the Movie model and metadata.
     *
     * @return void The rendered view as the response.
     */
    public function createMovie(){
        if($this->request->isPost()){
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
