<?php

namespace Core;

class Response
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    public function abort($code = 404)
    {
        http_response_code($code);
        $this->view->render($code);
        die();
    }

}
