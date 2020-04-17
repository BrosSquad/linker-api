<?php

namespace BrosSquad\Linker\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

abstract class ApiController
{
    /**
     * @param mixed $data
     *
     * Serialize the response to return JSON, with a custom HTTP status code
     */
    protected function response(Response $response, $data, int $statusCode): Response
    {
        $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode)
        ;
    }
}
