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

    /**
     * Renders a view file by including it in the current script execution.
     *
     * @param string $view The name of the view file to render.
     * @param array $data Optional. An array of data to pass to the view file.
     * @param string $path Optional. The path to the view file relative to the `viewPath` property.
     * @throws \Exception If the view file does not exist.
     */
    public function render($view, $data = [], $path = "")
    {
        $viewFile = $this->viewPath . $path . $view . '.php';
        $data['viewPath'] = $this->viewPath;
        $data['app'] = Application::$app;

        if (file_exists($viewFile)) {
            $data['base_url'] = $this->base_url;
            extract($data);
            require $viewFile;
        } else {
            throw new \Exception("View file not found: $view");
        }
    }
}

