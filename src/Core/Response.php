<?php

namespace Core;

class Response
{
    protected $view;

    public function __construct($base_url = NULL)
    {
        $this->view = new View($base_url);
    }
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    public function abort($code = 404, $message = '')
    {
        http_response_code($code);
        $optionals = [
            'data' => ['message' => $message],
            'path' => 'errors',
            'metadata' => [
                'title' => 'Error',
                'description' => 'Error page',
            ],
        ];
        $this->view->render($code, $optionals);
        die();
    }

    public function render($view, $optionals = [])
    {
        $this->view->render($view, $optionals);
    }
    public function setStatusCode ($code)
    {
        http_response_code($code);
    }

}
