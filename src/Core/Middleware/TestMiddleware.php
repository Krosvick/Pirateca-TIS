<?php 

namespace Core\Middleware;

use Core\BaseMiddleware;
use Core\Request;
use Core\Exceptions\ForbiddenException;


class TestMiddleware extends BaseMiddleware
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
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
        if ($this->container->get(Request::class)->isPost()) {
            if (!isset($this->container->get(Request::class)->getBody()['username'])) {
                throw new ForbiddenException("user name is required");
            }
        }
    }
}