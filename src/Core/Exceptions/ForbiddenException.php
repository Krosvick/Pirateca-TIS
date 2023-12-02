<?php

namespace Core\Exceptions;

class ForbiddenException extends \Exception
{
    protected $message;
    protected $code = 403;

    public function __construct($message)
    {
        $this->message = $message;
    }
}