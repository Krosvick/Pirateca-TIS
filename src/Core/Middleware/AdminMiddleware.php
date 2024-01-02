<?php 

namespace Core\Middleware;

use Core\Application;
use Core\BaseMiddleware;
use Core\Request;
use Core\Exceptions\ForbiddenException;


/**
 * Class AdminMiddleware
 *
 * This class extends the BaseMiddleware class and is responsible for checking if the current user is an admin before executing certain actions.
 * If the user is not an admin and the current action is in the specified list of actions, a ForbiddenException is thrown and an error message is displayed.
 */
class AdminMiddleware extends BaseMiddleware
{
    protected array $actions = [];

    /**
     * AdminMiddleware constructor.
     *
     * Constructs a new AdminMiddleware object with an optional array of actions that require admin permission.
     *
     * @param array $actions An optional array of actions that require admin permission.
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * Executes the method.
     *
     * This method checks if the current user is an admin using the Application::isAdmin() method.
     * If the user is not an admin and the current action is in the specified list of actions, a ForbiddenException is thrown and an error message is displayed.
     *
     * @return void
     * @throws ForbiddenException If the user is not an admin and the current action is in the specified list of actions.
     */
    public function execute()
    {
        if (!Application::isAdmin()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                Application::$app->session->setFlash('error', 'You do not have permission to access this page');
                throw new ForbiddenException("You do not have permission to access this page");
            }
        }
    }
}