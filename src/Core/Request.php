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
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function getUrl()
    {
        return $this->url;
    }

}
