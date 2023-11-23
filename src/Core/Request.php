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

    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
    }
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function isGet()
    {
        return $this->getMethod() === 'get';
    }
    public function isPost()
    {
        return $this->getMethod() === 'post';
    }
    public function isPut()
    {
        return $this->getMethod() === 'put';
    }
    public function isDelete()
    {
        return $this->getMethod() === 'delete';
    }
    public function isPatch()
    {
        return $this->getMethod() === 'patch';
    }
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
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function getUrl()
    {
        return $this->url;
    }

}
