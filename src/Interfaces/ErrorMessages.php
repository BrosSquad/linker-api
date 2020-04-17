<?php

namespace BrosSquad\Linker\Api\Interfaces;

interface ErrorMessages
{
    public const SERVER_ERROR = 'Server error, please try again later.';
    public const NOT_FOUND = 'The requested resource was not found.';
    public const UNAUTHORIZED = 'You are not authorized to perform this action.';
    public const INVALID_AUTHORIZATION_TYPE = 'Invalid authorization type.';
}
