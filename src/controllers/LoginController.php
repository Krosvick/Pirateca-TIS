<?php 

namespace Controllers;

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

    public function index()
    {
        if($this->request->isPost()){
            $body = $this->request->getBody();
            $username = $body['username'];
            $password = $body['password'];
            $user = new User();
            $user_data = $this->userDAO->findBy('username', $username);
            if($user_data){
                if(!password_verify($password, $user_data['hashed_password'])){
                    $user->addError('password', 'User name or password are not valid');
                }
            }else{
                $user->addError('username', 'User name or password are not valid');
            }
            if($user->hasErrors()){
                $data = [
                    'title' => 'Login',
                    'errors' => $user->getErrors()
                ];
                return $this->render("partials/login", $data);
            }
        }
        $data = [
            'title' => 'Login'
        ];
        return $this->render("partials/login", $data);
    }
}