<?php

namespace Core;


class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {

                // Extract the controller class and method
                list($controllerClass, $method) = explode('@', $route['controller']);
                
                $originalControllerClass = $controllerClass; //for debugging purposes
                $controllerClass = $this->getDirectory() . $controllerClass;
                $controllerClass = base_path($controllerClass . '.php');
                require $controllerClass;
                $controllerClass = $this->getNamespace() . $originalControllerClass;
                $controller = new $controllerClass();

                // Call the relevant method
                $controller->$method();

                
                return;

            }
        }

        $this->abort();
    }

    public function getNamespace()
    {
        $namespace = 'Controllers\\';

        return $namespace;
    }

    public function getDirectory()
    {
        $directory = 'controllers\\';

        return $directory;
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        require base_path("views/{$code}.php");

        die();
    }
}
