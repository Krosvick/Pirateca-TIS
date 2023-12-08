<?php

namespace Core;

class Response
{
    /**
     * The View instance used for rendering views.
     *
     * @var View
     */
    protected $view;

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
        header('Location: ' . $url);
        exit;
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
        $this->view->render($code, ['message' => $message], 'errors/');
        die();
    }

    /**
     * Renders a view with optional data.
     *
     * @param string $view The name of the view to render.
     * @param array $data Optional data to pass to the view.
     * @return void
     */
    public function render($view, $data = [])
    {
        $this->view->render($view, $data);
    }

    /**
     * Sets the HTTP response code for the current request.
     *
     * @param int $code The HTTP response code to be set.
     * @return void
     */
    public function setStatusCode($code)
    {
        http_response_code($code);
    }
}
