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

class UserController extends BaseController{
    private $userDAO;
    public $user;

    public function __construct($container, $routeParams) {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user');
        $this->userDAO = new UsersDAO();
    }

    public function registerUser($username, $password, $email, $role) {
        // You can add input validation and error handling here
        // For example, check if the username or email is already taken
        // Password should be hashed before storing in the database

        // Create a new user object
        $user = new User(null, $username, $password, $email, UserDAO::STATUS_ACTIVE, $role);

        // Register the user using the UserDAO
        $this->userDAO->register($user);

        // Implement code to handle the registration process, e.g., redirect to a login page
    }

    public function loginUser($username, $password) {
        // You should validate user input and handle authentication here
        // Fetch user data from the database using the UserDAO
        $user = $this->userDAO->findUserByUsername($username);

        if ($user) {
            // Verify the password using password_verify
            if (password_verify($password, $user->getPassword())) {
                // Password matches, the user is authenticated
                // Implement code to handle the successful login, e.g., set user session
            } else {
                // Password is incorrect
                // Implement code to handle login failure, e.g., display an error message
            }
        } else {
            // User not found
            // Implement code to handle login failure, e.g., display an error message
        }
    }

    public function updateUserProfile($userId, $newUsername, $newEmail, $newPassword) {
        // You can add input validation and error handling here
        // Password should be hashed before storing in the database

        // Fetch the user's data from the database
        $user = $this->userDAO->find($userId);

        if ($user) {
            // Update the user's data
            $user->setUsername($newUsername);
            $user->setEmail($newEmail);
            $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));

            // Update the user's profile using the UserDAO
            $this->userDAO->update($userId, $user);

            // Implement code to handle the update process, e.g., redirect to the user's profile page
        } else {
            // User not found
            // Implement code to handle the update failure, e.g., display an error message
        }
    }

    public function deleteUser($userId) {
        // You can implement a soft delete or hard delete based on your requirements
        // Implement code to handle user deletion using the UserDAO
        $this->userDAO->delete($userId);

        // Implement code to handle the deletion process, e.g., redirect to a confirmation page
    }

    public function LikedMovies(){
        //exception if the user is not logged in
        if(!$this->user){
            echo "You are not logged in";
            $this->response->abort(404);
        }
        $ratingsDAO = new RatingsDAO();
        $MoviesDAO = new MoviesDAO();

        $user_movies = $this->user->get_liked_movies($ratingsDAO, $MoviesDAO, 10);
        $data = [
            'user_movies' => $user_movies
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

    public function ProfilePage(){
        if(!$this->user){
            echo "You are not logged in";
            $this->response->abort(404);
        }

        $user = Application::$app->session->get('user');
        dd($user);

        $data = [
            'user' => $user
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

        return $this->render('profile', $optionals);

    }

}
