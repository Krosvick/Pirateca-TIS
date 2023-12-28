<?php

namespace Controllers;

use DAO\UsersDAO;
use Core\BaseController;
use DAO\moviesDAO;
use DAO\RatingsDAO;
use Models\User;
use Models\Movie;
use Core\Application;
use GuzzleHttp\Client;

/**
 * UserController class
 *
 * This class is responsible for handling user-related functionality in the application.
 */
class UserController extends BaseController{
    private $userDAO;
    public $user;

    /**
     * UserController constructor
     *
     * Initializes the UserController class by calling the parent constructor and setting the user and userDAO properties.
     *
     * @param object $container An object representing the application's container.
     * @param array $routeParams An array containing the route parameters.
     * @return void
     */
    public function __construct($container, $routeParams) {
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user');
        $this->userDAO = new UsersDAO();
    }

    /**
     * LikedMovies method
     *
     * Renders a page that displays the movies liked by a user.
     *
     * @return string The rendered HTML content of the "likedpost" template.
     */
    public function LikedMovies(){
        if(!$this->user){
            echo "You are not logged in";
            $this->response->abort(404);
        }
        $ratingsDAO = new RatingsDAO();
        $MoviesDAO = new MoviesDAO();

        $username = $this->user->get_username();
        $user_movies = $this->user->get_liked_movies($ratingsDAO, $MoviesDAO, 10);
        $data = [
            'user_movies' => $user_movies,
            'username' => $username
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.',
            'cssFiles' => [
                '' // TODO: add css files here
            ],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];

        return $this->render("likedpost", $optionals);
    }

    /**
     * ProfilePage method
     *
     * Checks if the user is logged in and renders the profile page.
     *
     * @return string The rendered HTML content of the "profile" template.
     */
    public function ProfilePage()
    {
        if (!$this->user) {
            echo "You are not logged in";
            $this->response->abort(404);
        }

        $user = Application::$app->session->get('user');

        $data = [
            'user' => $user
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.',
            'cssFiles' =>  ['styles_nav.css'],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];

        return $this->render('profile', $optionals);
    }

    /**
     * Logout method
     *
     * Logs out the user by clearing the user session and redirecting the user to the homepage.
     *
     * @return void
     */
    public function logout(){
        if($this->user){
            Application::$app->logout();
            header('Location: /');
        }
        else{
            header('Location: /');
        }
    }
}
