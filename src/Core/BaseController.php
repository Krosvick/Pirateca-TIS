<?php 

namespace Core;


abstract class BaseController
{
    protected $routeParams;
    protected $response;
    protected $request;

    public function __construct($container, $routeParams)
    {
        $this->routeParams = $routeParams;
        $this->response = $container->get(Response::class);
        $this->request = $container->get(Request::class);
    }

    protected function before()
    {
        // This method can be overridden in child classes for pre-action logic
    }

    protected function after()
    {
        // This method can be overridden in child classes for post-action logic
    }

    protected function render($view, $data = [])
    {
        $this->response->render($view, $data);
    }

    protected function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

