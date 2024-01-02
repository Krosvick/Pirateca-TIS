<?php 

namespace Core\Middleware;

use Core\Application;
use Core\BaseMiddleware;
use Core\Request;
use Core\Exceptions\ForbiddenException;


/**
 * Class AuthMiddleware
 *
 * This class extends the BaseMiddleware class and provides functionality for authentication middleware.
 */
class AuthMiddleware extends BaseMiddleware
{
    /**
     * @var array $actions An array of actions that are allowed without authentication.
     */
    protected array $actions = [];

    /**
     * AuthMiddleware constructor.
     *
     * Constructs a new AuthMiddleware object with optional allowed actions.
     *
     * @param array $actions An optional array of actions that are allowed without authentication.
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * Executes the middleware.
     *
     * This method checks if the user is a guest and if the current action is in the list of allowed actions.
     * If the user is a guest and the action is not in the allowed actions, it sets a flash message and redirects the user to the login page.
     *
     * @return void
     */
    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                Application::$app->session->setFlash('error', 'You are not logged in');
                header('Location: /login');
            }
        }
    }
}
