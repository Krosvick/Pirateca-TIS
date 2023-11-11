<?php

namespace Core;

//routes will be added follwing the format below
//$router->addRoute('/', 'indexController@index');
//where the first parameter is the url and the second is the controller and method to be called

class Router
{
    protected $routes = [];
    protected $params = [];
    protected $request;
    protected $response;
    protected $router;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

    }
    public function addRoute($url, $handler)
    {
        // Split the handler into controller and action
        list($controller, $action) = explode('@', $handler);

        // Add route with controller and action as params
        $this->routes[$url] = [
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function matchRoute($url)
    {
        foreach ($this->routes as $route => $params) {
            if ($url == $route) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function dispatch()
    {
        $url = $this->request->getUrl();
        $url = $this->removeQueryStringVariables($url);

        if (isset($this->routes[$url])) {
            $params = $this->routes[$url];

            $controller = $this->getNamespace() . $this->toStudlyCaps($params['controller']);
            if (class_exists($controller)) {
                $controller_object = new $controller($params);
                $action = $this->toCamelCase($params['action']);

                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    $this->response->abort(404);
                }
            } else {
                $this->response->abort(404);
            }
        } else {
            $this->response->abort(404);
        }
    }


    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }

        }
        return $url;
    }

    protected function toStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function toCamelCase($string)
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    public function getNamespace()
    {
        $namespace = 'Controllers\\';

        return $namespace;
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

}
