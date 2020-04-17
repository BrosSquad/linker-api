<?php

namespace BrosSquad\Linker\Api\Middleware;

use BrosSquad\Linker\Api\Interfaces\ErrorMessages;
use BrosSquad\Linker\Api\Services\KeyService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CheckTokenMiddleware
{
    protected KeyService $keyService;

    public function __construct(KeyService $keyService)
    {
        $this->keyService = $keyService;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $authorization = $request->getHeader('Authorization');

        if (0 === count($authorization)) {
            $request = $request->withAttribute('error', ErrorMessages::UNAUTHORIZED);
            // TODO: LOG HERE
            return $handler->handle($request);
        }

        $split = explode(' ', $authorization);

        if (2 !== count($split) || 'bearer' !== strtolower($split[0])) {
            $request = $request->withAttribute('error', ErrorMessages::INVALID_AUTHORIZATION_TYPE);
            // TODO: LOG HERE
            return $handler->handle($request);
        }

        if (!preg_match('#^BrosSquad\.\d+\.[a-zA-Z0-9\_\-]{80,90}\.[a-zA-Z0-9\_\-]+#', $split[1])) {
            return $handler->handle($request);
        }

        if (!$this->keyService->verify($split[1])) {
            $request = $request->withAttribute('error', ErrorMessages::INVALID_AUTHORIZATION_TYPE);
            // TODO: LOG HERE
            return $handler->handle($request);
        }

        return $handler->handle($request);
    }
}
