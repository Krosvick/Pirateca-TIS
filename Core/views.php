<?php

namespace Core;

class View
{
    protected $viewPath = base_path('views/');

    public function __construct($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    public function render($view, $data = [])
    {
        $viewFile = $this->viewPath . $view . '.php';

        if (file_exists($viewFile)) {
            extract($data);
            require $viewFile;
        } else {
            throw new \Exception("View file not found: $view");
        }
    }
}

