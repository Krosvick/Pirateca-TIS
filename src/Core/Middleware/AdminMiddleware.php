<?php 

namespace Core\Middleware;

use Core\Application;
use Core\BaseMiddleware;
use Core\Request;
use Core\Exceptions\ForbiddenException;


class AdminMiddleware extends BaseMiddleware
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
        if(!Application::isAdmin()){
            if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)){
                Application::$app->session->setFlash('error', 'You do not have permission to access this page');
                throw new ForbiddenException("You do not have permission to access this page");
            }
        }
    }
}