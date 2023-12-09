<?php

namespace Core;

class Request
{
    protected $url;
    protected $base_url;

    public function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];

    }

    /**
     * Sets the base URL for the request.
     *
     * @param string $base_url The base URL to be set.
     * @return void
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
    }
    /**
     * Retrieves the HTTP request method.
     *
     * @return string The HTTP request method (e.g. 'get', 'post').
     */
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Checks if the request method is 'get'.
     *
     * @return bool True if the request method is 'get', false otherwise.
     */
    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    /**
     * Checks if the request method is 'post'.
     *
     * @return bool True if the request method is 'post', false otherwise.
     */
    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    /**
     * Checks if the request method is 'put'.
     *
     * @return bool True if the request method is 'put', false otherwise.
     */
    public function isPut()
    {
        return $this->getMethod() === 'put';
    }

    /**
     * Checks if the request method is 'delete'.
     *
     * @return bool True if the request method is 'delete', false otherwise.
     */
    public function isDelete()
    {
        return $this->getMethod() === 'delete';
    }

    /**
     * Checks if the request method is 'patch'.
     *
     * @return bool True if the request method is 'patch', false otherwise.
     */
    public function isPatch()
    {
        return $this->getMethod() === 'patch';
    }

    /**
     * Retrieves the sanitized request body parameters.
     *
     * @return array An array containing the sanitized request body parameters.
     */
    public function getBody()
    {
        $body = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    /**
     * Retrieves the base URL for the request.
     *
     * @return string The base URL for the request.
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * Retrieves the sanitized URL for the request.
     *
     * @return string The sanitized URL for the request.
     */
    public function getUrl()
    {
        return filter_var($this->url, FILTER_SANITIZE_URL);
    }

}
