<?php

namespace BrosSquad\Linker\Api\Middleware;

use BrosSquad\Linker\Api\Services\CheckTokenService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Throwable;

class CheckTokenMiddleware
{
    protected CheckTokenService $checkTokenService;

    public function __construct(CheckTokenService $checkTokenService)
    {
        $this->checkTokenService = $checkTokenService;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $authorization = $request->getHeader('Authorization');

        try {
            $this->checkTokenService->check($authorization);
        } catch (Throwable $e) {
            $request->withAttribute('error', $e->getMessage());
        }

        return $handler->handle($request);
    }
}
