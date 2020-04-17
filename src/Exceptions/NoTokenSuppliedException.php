<?php

namespace BrosSquad\Linker\Api\Exceptions;

use BrosSquad\Linker\Api\Interfaces\ErrorMessages;
use Exception;

class NoTokenSuppliedException extends Exception
{
    public function __construct(string $message = ErrorMessages::UNAUTHORIZED)
    {
        parent::__construct($message);
    }
}
