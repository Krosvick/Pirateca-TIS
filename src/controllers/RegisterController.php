<?php

namespace  Controllers;

use Core\BaseController;
use Core\Middleware\TestMiddleware;
use Models\User;
use DAO\UsersDAO;
use Core\Auth\PasswordTrait;
use Core\Application;

class RegisterController extends BaseController
{
    use PasswordTrait;
    private $userDAO;
    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->userDAO = new UsersDAO();
    }


    /**
     * Handles the registration process for a user.
     *
     * @return mixed The rendered registration page or a redirect response.
     */
    public function index()
    {
        $user = new User();
        if($this->request->isPost()){
            $body = (object) $this->request->getBody();
            if (!isset($body->username) || !isset($body->password) || !isset($body->confirm_password)) {
                $this->response->redirect('/register');
            }
            $user->loadData($body);
            $user->set_hashed_password($this->cryptPassword($body->password));
            $user->set_created_at(date('Y-m-d H:i:s'));
            if($user->validate()){
                unset($user->password);
                $this->userDAO->register($user);
                Application::$app->session->setFlash('success', 'Thanks for registering');
                $this->response->redirect('/login');
            }
        }
        $data = [
            'title' => 'Register',
            'errors' => $user->getErrors()
        ];
        $metadata = [
            'title' => 'Register',
            'description' => 'Register page',
            'cssFiles' => [
                'styles_login.css',
            ],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata,
        ];
        return $this->render("register", $optionals);
    }
}