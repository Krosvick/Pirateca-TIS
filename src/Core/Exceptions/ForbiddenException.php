<?php

namespace Core\Exceptions;

/**
 * Class ForbiddenException
 *
 * This class represents a ForbiddenException that extends the \Exception class.
 * It is used to handle exceptions related to forbidden access.
 */
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
