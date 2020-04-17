<?php

namespace BrosSquad\Linker\Api\Exceptions;

use BrosSquad\Linker\Api\Interfaces\ErrorMessages;
use Exception;

class AuthenticationTypeException extends Exception
{
    public function __construct(string $message = ErrorMessages::INVALID_AUTHORIZATION_TYPE)
    {
        parent::__construct($message);
    }
}
