<?php

namespace BrosSquad\Linker\Api\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    public function __construct(string $message = 'Invalid Token')
    {
        parent::__construct($message);
    }
}
