<?php

namespace BrosSquad\Linker\Api\Middleware;

use BrosSquad\Linker\Api\Interfaces\HttpStatusCodes;
use BrosSquad\Linker\Api\Services\CheckTokenService;
use BrosSquad\Linker\Api\Traits\ResponseTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Throwable;

class CheckTokenMiddleware
{
    use ResponseTrait;

    protected CheckTokenService $checkTokenService;

    public function __construct(CheckTokenService $checkTokenService)
    {
        $this->checkTokenService = $checkTokenService;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $authorization = $request->getHeader('Authorization');

        try {
            $apiKey = $this->checkTokenService->check($authorization);
        } catch (Throwable $e) {
            return $this->response(new Response(), ['error' => $e->getMessage()], HttpStatusCodes::UNAUTHORIZED);
        }

        return $handler->handle($request);
    }
}
