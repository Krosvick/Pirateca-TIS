<?php

namespace Core;

class Request
{
    protected $url;

    public function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];
    }

    public function getUrl()
    {
        return $this->url;
    }

}
