<?php

namespace Core;

/**
 * Class Response
 *
 * The Response class handles HTTP responses in a PHP application.
 * It provides methods for redirecting the user to a different URL,
 * aborting the request with an error message, rendering a view,
 * and setting the HTTP response code.
 */
class Response
{
    /**
     * The View instance used for rendering views.
     *
     * @var View
     */
    protected $view;
    protected $content;

    /**
     * Create a new Response instance.
     *
     * @param string|null $base_url The base URL for the application.
     * @return void
     */
    public function __construct($base_url = null)
    {
        $this->view = new View($base_url);
    }

    /**
     * Redirect the user to a different URL.
     *
     * @param string $url The URL to redirect to.
     * @return void
     */
    public function redirect($url)
    {
        if (Application::$app->user) {
            $user = Application::$app->user;
            unset($user->DAOs);
            Application::$app->user = $user;
        }
        header('Location: ' . $url);
        exit();
    }

    /**
     * Abort the request with an error message.
     *
     * @param int $code The HTTP response code.
     * @param string $message The error message.
     * @return void
     */
    public function abort($code = 404, $message = '')
    {
        http_response_code($code);
        $optionals = [
            'data' => ['error_message' => $message, 'code' => $code],
            'path' => 'errors',
            'metadata' => [
                'title' => 'Error',
                'description' => 'Error page',
            ],
        ];
        $this->view->render("errorPage", $optionals);
        die();
    }

    /**
     * Renders a view using the View instance.
     *
     * @param string $view The name of the view to render.
     * @param array $optionals Optional data and parameters to pass to the view.
     * @return void
     */
    public function render($view, $optionals = [])
    {
        $this->view->render($view, $optionals);
    }
    
    /**
     * Sets the HTTP response code for the current request.
     *
     * @param int $code The HTTP response code to set.
     * @return void
     */
    public function setStatusCode($code)
    {
        http_response_code($code);
    }
    public function setContent($content)
    {
        $this->content = $content;
    }
    public function send()
    {
        echo $this->content;
    }
}