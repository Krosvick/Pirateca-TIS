<?php

namespace Core;

//routes will be added follwing the format below
//$router->addRoute('/', 'indexController@index');
//where the first parameter is the url and the second is the controller and method to be called

class Router
{
    protected $routes = ['GET' => [], 'POST' => [], 'PUT' => [], 'DELETE' => []];
    protected $params = [];
    protected $request;
    protected $response;
    private $container;
    public function __construct(Container $container)
    {
        $this->request = $container->get(Request::class);
        $this->response = $container->get(Response::class);
        $this->container = $container;
    }

    public function get($url, $handler)
    {
        $this->addRoute('get',$url, $handler);
    }
    public function post($url, $handler)
    {
        $this->addRoute('post',$url, $handler);
    }
    public function put($url, $handler)
    {
        $this->addRoute('put',$url, $handler);
    }
    public function delete($url, $handler)
    {
        $this->addRoute('delete',$url, $handler);
    }
    private function addRoute($method, $url, $handler)
    {
        // Split the handler into controller and action
        list($controller, $action) = explode('@', $handler);

        // Extract dynamic segments from the URL
        $segments = [];
        $pattern = preg_replace_callback('/{([a-z]+)}/', function ($matches) use (&$segments) {
            $segments[] = $matches[1];
            return '([^/]+)';
        }, $url);

        $pattern = "#^$pattern$#";

        // Add route with controller, action, and segments as params
        $this->routes[$method][$url] = [
            'controller' => $controller,
            'action' => $action,
            'segments' => $segments,
        ];
    }


    public function getRoutes()
    {
        return $this->routes;
    }

    public function matchRoute($method, $url)
    {
        foreach ($this->routes[$method] as $route => $params) {
            // Check if the route has dynamic segments
            $pattern = preg_replace_callback('/{[a-z]+}/', function ($matches) {
                return '([^/]+)';
            }, $route);
            $pattern = "#^$pattern$#";

            if (preg_match($pattern, $url, $matches)) {
                // Extract values for dynamic segments
                array_shift($matches);
                $this->params = array_merge($params, array_combine($params['segments'], $matches));
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
        $method = $this->request->getMethod();
        $url = $this->removeQueryStringVariables($url);

        if ($this->matchRoute($method, $url)) {
            $params = $this->params;

            $controller = $this->getNamespace() . $this->toStudlyCaps($params['controller']);
            if (class_exists($controller)) {
                $controllerObject = new $controller($this->container, $params);
                $action = $this->toCamelCase($params['action']);

                if (is_callable([$controllerObject, $action])) {
                    foreach ($controllerObject->getMiddleware() as $middleware) {
                        $middleware->execute();
                    }
                    if (isset($params['id'])) {
                        $controllerObject->$action($params['id']);
                    } else {
                        $controllerObject->$action();
                    }
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
