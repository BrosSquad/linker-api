<?php

namespace BrosSquad\Linker\Api\Services;

use BrosSquad\Linker\Api\Exceptions\AuthenticationTypeException;
use BrosSquad\Linker\Api\Exceptions\InvalidTokenException;
use BrosSquad\Linker\Api\Exceptions\NoTokenSuppliedException;

class CheckTokenService
{
    protected KeyService $keyService;

    public function __construct(KeyService $keyService)
    {
        $this->keyService = $keyService;
    }

    public function check(array $authorization): string
    {
        if (0 === count($authorization)) {
            throw new NoTokenSuppliedException();
        }

        $authorization = $authorization[0];
        $split = explode(' ', $authorization);

        if (2 !== count($split) || 'bearer' !== strtolower($split[0])) {
            throw new AuthenticationTypeException();
        }

        if (!preg_match('#^BrosSquadLinker\.[A-Za-z0-9-_]+\.?[A-Za-z0-9-_.+]*$#', $split[1])) {
            throw new InvalidTokenException();
        }

        if (!$this->keyService->verify($split[1])) {
            throw new InvalidTokenException();
        }

        return $split[1];
    }
}
