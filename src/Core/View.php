<?php

namespace Core;

class View
{
    protected $viewPath;

    public function __construct()
    {
        $this->viewPath = base_path("src/views/");
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

