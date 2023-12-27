<?php 

namespace Controllers;

use Core\Application;
use Core\BaseController;
use DAO\UsersDAO;
use Models\User;

class LoginController extends BaseController
{
    private $userDAO;
    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->userDAO = new UsersDAO();
    }

    /**
     * Handles the login functionality.
     *
     * Retrieves the username and password from the request body,
     * calls the login() method of the User class to validate the credentials,
     * and redirects the user to the home page if the login is successful.
     *
     * @return void
     */
    public function index()
    {
        if($this->request->isPost()){
            $body = $this->request->getBody();
            $username = $body['username'];
            $password = $body['password'];
            $user = new User();
            $user_data = $user->login($this->userDAO, $username, $password);
            //dd($user->getErrors());
            if($user->hasErrors()){
                $data = [
                    'title' => 'Login',
                    'errors' => $user->getErrors()
                ];
                $metadata = [
                    'title' => 'Login',
                    'description' => 'Login page',
                ];
                $optionals = [
                    'data' => $data,
                    'metadata' => $metadata
                ];

                return $this->render("login", $optionals);
            }
            #map the user data to the user object
            $user->loadData($user_data);
            if(Application::$app->login($user)){
                $this->response->redirect('/');
                return;
            }
            
        }
        $data = [
            'title' => 'Login'
        ];
        $metadata = [
            'title' => 'Login',
            'description' => 'Login page',
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];
        return $this->render("login", $optionals);
    }
}