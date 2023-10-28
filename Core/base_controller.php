<?php 

namespace Core;

abstract class BaseController
{
    protected $routeParams;
    protected $view;

    public function __construct($routeParams)
    {
        $this->routeParams = $routeParams;
        $this->view = new View(__DIR__ . '/../views/'); // Set the view path here
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
        $this->view->render($view, $data);
    }

    protected function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    public function notFoundAction()
    {
        http_response_code(404);
        echo '404 - Not Found';
    }
}

