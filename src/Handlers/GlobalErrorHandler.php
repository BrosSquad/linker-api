<?php

namespace BrosSquad\Linker\Api\Handlers;

use Monolog\Logger;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;

class GlobalErrorHandler extends ErrorHandler
{
    public function __construct(CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory, Logger $logger)
    {
        parent::__construct($callableResolver, $responseFactory, $logger);
    }

    public function logError(string $error): void
    {
        $this->logger->error($error);
    }
}
