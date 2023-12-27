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
   
    /**
     * Router constructor.
     *
     * Initializes the request, response, and container properties of the class.
     *
     * @param Container $container An instance of the Container class.
     */
    public function __construct(Container $container)
    {
        $this->request = $container->get(Request::class);
        $this->response = $container->get(Response::class);
        $this->container = $container;
    }


    /**
     * Adds a route to the `routes` array for the HTTP GET method.
     *
     * @param string $url The URL pattern for the route.
     * @param string $handler The handler for the route, in the format `Controller@method`.
     * @return void
     */
    public function get($url, $handler)
    {
        $this->addRoute('get', $url, $handler);
    }
    /**
     * Adds a route for the HTTP POST method.
     *
     * @param string $url The URL pattern for the route.
     * @param string $handler The handler for the route, in the format `Controller@action`.
     * @return void
     */
    public function post($url, $handler)
    {
        $this->addRoute('post', $url, $handler);
    }
    /**
     * Adds a route for the HTTP PUT method.
     *
     * @param string $url The URL pattern for the route.
     * @param string $handler The handler for the route, in the format "Controller@method".
     * @return void
     */
    public function put($url, $handler)
    {
        $this->addRoute('put', $url, $handler);
    }
    /**
     * Adds a route for the DELETE HTTP method to the list of routes.
     *
     * @param string $url The URL pattern for the route.
     * @param string $handler The handler for the route, in the format 'Controller@action'.
     * @return void
     */
    public function delete($url, $handler)
    {
        $this->addRoute('delete', $url, $handler);
    }
    /**
     * Adds a new route to the `routes` array.
     *
     * @param string $method The HTTP method of the route (e.g., 'GET', 'POST', 'PUT', 'DELETE').
     * @param string $url The URL of the route.
     * @param string $handler The handler for the route, in the format 'controller@action'.
     * @return void
     */
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


    /**
     * Returns the routes array.
     *
     * @return array The routes array, which contains the routes for different HTTP methods.
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    /**
     * Matches a given URL and HTTP method to a route in the `routes` array.
     * If a match is found, it extracts the values for dynamic segments in the URL
     * and stores them in the `params` property.
     *
     * @param string $method The HTTP method of the request (e.g., 'GET', 'POST', 'PUT', 'DELETE').
     * @param string $url The URL of the request.
     * @return bool Returns true if a route is matched and the values for dynamic segments are extracted,
     *              false if no route is matched.
     */
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
                $params = array_merge($params, array_combine($params['segments'], $matches));

                $params = array_merge($params, $this->request->getBody());
                $this->params = $params;
                
                return true;
            }
        }

        return false;
    }


    /**
     * Returns the value of the $params property.
     *
     * @return array The value of the $params property, which is an array containing the controller, action, and segments for the current route.
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Dispatches the request to the appropriate controller and action based on the URL and HTTP method.
     *
     * @return void
     */
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

                        if(isset($params['offset'])){
                            $controllerObject->$action($params['id'],$params['offset']);
                        }
                        else {
                            $controllerObject->$action($params['id']);
                        }

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



    /**
     * Removes query string variables from a given URL.
     *
     * @param string $url The URL from which to remove query string variables.
     * @return string The modified URL with any query string variables removed.
     */
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

    /**
     * Converts a string to studly caps format.
     *
     * @param string $string The string to be converted.
     * @return string The input string converted to studly caps format.
     */
    protected function toStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Converts a string to camel case by making the first letter lowercase.
     *
     * @param string $string The string to be converted to camel case.
     * @return string The string in camel case format.
     */
    protected function toCamelCase($string)
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    /**
     * Returns the namespace string 'Controllers\\'.
     *
     * @return string The namespace string 'Controllers\\'.
     */
    public function getNamespace()
    {
        $namespace = 'Controllers\\';

        return $namespace;
    }

    /**
     * Returns the URL of the previous page visited by the user.
     *
     * @return string The URL of the previous page.
     */
    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

}
