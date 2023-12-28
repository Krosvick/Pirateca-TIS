<?php 

namespace Core\Middleware;

use Core\Application;
use Core\BaseMiddleware;
use Core\Request;
use Core\Exceptions\ForbiddenException;


class AuthMiddleware extends BaseMiddleware
{
    protected array $actions = [];
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }
    /**
     * Executes the method.
     *
     * This method checks if a POST request is being made and if the request body contains a 'username' field.
     * If the 'username' field is not present, it throws a ForbiddenException with the message "user name is required".
     *
     * @return void
     */
    public function execute()
    {
        if(Application::isGuest()){
            
            if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)){
                Application::$app->session->setFlash('error', 'You are not logged in');
                header('Location: /login');
            }
        }
    }
}