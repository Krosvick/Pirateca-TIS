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
        if($this->container->get(Request::class)->isPost()){
            if(!isset($this->container->get(Request::class)->getBody()['username'])){
                throw new ForbiddenException("user name is required");
            }
        }
    }
}