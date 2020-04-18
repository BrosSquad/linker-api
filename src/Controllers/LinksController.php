<?php

namespace BrosSquad\Linker\Api\Controllers;

use BrosSquad\Linker\Api\Interfaces\ErrorMessages;
use BrosSquad\Linker\Api\Interfaces\HttpStatusCodes;
use BrosSquad\Linker\Api\Services\LinkService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;

class LinksController extends ApiController
{
    protected LinkService $linkService;

    private Validator $validator;

    private Logger $logger;

    public function __construct(LinkService $linkService, Validator $validator, Logger $logger)
    {
        $this->linkService = $linkService;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    public function get(Request $request, Response $response): Response
    {
        return $this->response(
            $response,
            $this->linkService->get(),
            HttpStatusCodes::OK
        );
    }

    public function find(Request $request, Response $response, $id): Response
    {
        try {
            return $this->response(
                $response,
                $this->linkService->find(intval($id)),
                HttpStatusCodes::OK
            );
        } catch (ModelNotFoundException $e) {
            $this->logger->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
                'requestParam' => $id,
            ]);

            return $this->response(
                $response,
                ['error' => ErrorMessages::NOT_FOUND],
                HttpStatusCodes::NOT_FOUND
            );
        }
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $body = $request->getParsedBody();

            $valiation = $this->validator->validate($body, [
                'url' => 'required|max:300',
            ]);

            if ($valiation->fails()) {
                return $this->response($response, ['error' => $valiation->errors()->firstOfAll()], HttpStatusCodes::UNPROCESSABLE_ENTITY);
            }

            return $this->response($response, $this->linkService->create($body['url']), HttpStatusCodes::CREATED);
        } catch (QueryException | Exception $e) {
            $this->logger->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
                'requestBody' => $body,
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request, Response $response, $id): Response
    {
        try {
            $this->linkService->delete(intval($id));

            return $this->response($response, null, HttpStatusCodes::NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            $this->logger->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
                'param' => $id,
            ]);

            return $this->response(
                $response,
                ['error' => ErrorMessages::NOT_FOUND],
                HttpStatusCodes::NOT_FOUND
            );
        }
    }
}
