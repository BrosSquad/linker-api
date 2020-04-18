<?php

/** @var Slim\App */

use BrosSquad\Linker\Api\Controllers\KeysController;
use BrosSquad\Linker\Api\Controllers\LinksController;
use BrosSquad\Linker\Api\Middleware\CheckTokenMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', static function (RouteCollectorProxy $group) {
    $group->group('/keys', static function (RouteCollectorProxy $group) {
        $group->get('', [KeysController::class, 'get']);
        $group->post('', [KeysController::class, 'create']);
        $group->delete('{id}', [KeysController::class, 'delete']);
    });

    $group->group('/links', static function (RouteCollectorProxy $group) {
        $group->get('', [LinksController::class, 'get']);
        $group->get('{id}', [LinksController::class, 'find']);
        $group->post('', [LinksController::class, 'create']);
        $group->delete('{id}', [LinksController::class, 'delete']);
    });
})->add(CheckTokenMiddleware::class);
