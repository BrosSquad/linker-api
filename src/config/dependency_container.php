<?php

use DI\ContainerBuilder;
use function DI\create;
use Illuminate\Database\Capsule\Manager as Capsule;
use MongoDB\Client as MongoDBClient;
use Monolog\Handler\MongoDBHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

$containerBuilder = new ContainerBuilder();

$capsule = require __DIR__.'/../db/db.php';

$containerBuilder->addDefinitions([
    Capsule::class => static fn () => $capsule,
    MongoDBClient::class => create(MongoDBClient::class)->constructor($_ENV['MONGODB_CONNECTION_STRING']),
    Logger::class => static function (ContainerInterface $container) {
        $logger = new Logger($_ENV['MONOLOG_LOGGER_NAME']);
        $logger->pushHandler(new MongoDBHandler($container->get(MongoDBClient::class), $_ENV['MONGODB_DATABASE'], $_ENV['MONGODB_LOGS_COLLECTION']));

        return $logger;
    },
]);

return $containerBuilder->build();
