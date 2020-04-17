<?php

use BrosSquad\Linker\Api\Handlers\GlobalErrorHandler;
use DI\Bridge\Slim\Bridge;
use Dotenv\Dotenv;
use Monolog\Logger;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$container = require_once __DIR__.'/../src/config/dependency_container.php';

$app = Bridge::create($container);
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$globalErrorHandler = new GlobalErrorHandler($app->getCallableResolver(), $app->getResponseFactory(), $container->get(Logger::class));
$globalErrorHandler->forceContentType('application/json');

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($globalErrorHandler);

// ROUTES HERE

$app->run();
