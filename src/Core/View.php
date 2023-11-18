<?php

namespace Core;

class View
{
    protected $viewPath;
    protected $base_url;

    public function __construct($base_url = NULL)
    {
        $this->viewPath = base_path("src/views/");
        $this->base_url = $base_url ?? "/";
    }

    public function render($view, $data = [])
    {
        $viewFile = $this->viewPath . $view . '.php';

        if (file_exists($viewFile)) {
            $data['base_url'] = $this->base_url;
            extract($data);
            require $viewFile;
        } else {
            throw new \Exception("View file not found: $view");
        }
    }
}

