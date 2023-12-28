<?php 

namespace Core;


/**
 * Abstract class representing a base controller.
 *
 * This class contains various methods for rendering views, redirecting users, returning JSON data, and managing middleware.
 *
 * Example Usage:
 * ```php
 * $controller = new BaseController($container, $routeParams);
 * $controller->render('home', ['title' => 'Welcome']);
 * $controller->redirect('/login');
 * $controller->json(['message' => 'Success']);
 * $controller->registerMiddleware(new AuthMiddleware());
 * $middleware = $controller->getMiddleware();
 * ```
 *
 * @package YourPackage
 */
abstract class BaseController
{
    protected $routeParams;
    protected $response;
    protected $request;
    protected array $middleware = [];

    /**
     * Constructor.
     *
     * Initializes the BaseController object.
     *
     * @param object $container An object representing the dependency injection container.
     * @param array $routeParams An array containing the route parameters.
     */
    public function __construct($container, $routeParams)
    {
        $this->routeParams = $routeParams;
        $this->response = $container->get(Response::class);
        $this->request = $container->get(Request::class);
    }

    /**
     * Method to be overridden in child classes for pre-action logic.
     *
     * This method can be overridden in child classes to add pre-action logic.
     */
    protected function before()
    {
        // This method can be overridden in child classes for pre-action logic
    }

    /**
     * Method to be overridden in child classes for post-action logic.
     *
     * This method can be overridden in child classes to add post-action logic.
     */
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