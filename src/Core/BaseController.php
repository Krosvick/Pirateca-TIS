<?php 

namespace Core;


abstract class BaseController
{
    protected $routeParams;
    protected $response;
    protected $request;
    protected array $middleware = [];

    public function __construct($container, $routeParams)
    {
        $this->routeParams = $routeParams;
        $this->response = $container->get(Response::class);
        $this->request = $container->get(Request::class);
    }

    protected function before()
    {
        // This method can be overridden in child classes for pre-action logic
    }

    protected function after()
    {
        // This method can be overridden in child classes for post-action logic
    }

    /**
     * Renders a view.
     *
     * @param string $view The name of the view to render.
     * @param array $optionals Optional data to pass to the view.
     * @return void
     */
    protected function render($view, $optionals = [])
    {
        $this->response->render($view, $optionals);
    }

    /**
     * Redirects the user to a different URL using the HTTP `Location` header.
     *
     * @param string $url The URL to redirect to.
     * @param int $statusCode The HTTP status code to use for the redirect. Default is `302`.
     * @return void
     */
    protected function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    /**
     * Sets the Content-Type header to application/json and echoes the JSON-encoded version of the provided data.
     *
     * @param mixed $data The data to be encoded as JSON.
     * @return void
     */
    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Adds a middleware to the array of middleware stored in the `$middleware` property.
     *
     * @param mixed $middleware The middleware to be added to the array.
     * @return void
     */
    public function registerMiddleware($middleware)
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Returns the array of middleware stored in the `$middleware` property.
     *
     * @return array The array of middleware.
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }
}

