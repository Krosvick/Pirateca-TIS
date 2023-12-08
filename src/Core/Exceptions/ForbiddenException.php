<?php

namespace Core\Exceptions;

class ForbiddenException extends \Exception
{
    protected $message;
    protected $code = 403;

    /**
     * Class constructor for the ForbiddenException class.
     *
     * @param string $message The error message to be assigned to the `message` property.
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}