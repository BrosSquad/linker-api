<?php

use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;

$containerBuilder = new ContainerBuilder();

$capsule = require __DIR__.'/../db/db.php';

$containerBuilder->addDefinitions([
    Capsule::class => static fn () => $capsule,
]);

return $containerBuilder->build();
