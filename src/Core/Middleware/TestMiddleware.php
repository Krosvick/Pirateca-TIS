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
    public function execute()
    {
        if($this->container->get(Request::class)->getBody()["email"] == "gundam43125@gmail.com"){
            throw new ForbiddenException("Email is required");
        }
    }
}