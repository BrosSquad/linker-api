<?php

namespace BrosSquad\Linker\Api\Controllers;

use BrosSquad\Linker\Api\Interfaces\ErrorMessages;
use BrosSquad\Linker\Api\Interfaces\HttpStatusCodes;
use BrosSquad\Linker\Api\Services\KeyService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;
use Throwable;

/**
 * Class KeysController.
 */
class KeysController extends ApiController
{
    protected KeyService $keyService;

    protected Validator $validator;

    public function __construct(KeyService $keyService, Validator $validator)
    {
        $this->keyService = $keyService;
        $this->validator = $validator;
    }

    /**
     * Gets all keys from database.
     */
    public function get(Request $request, Response $response): Response
    {
        $urlQueryStrings = $request->getQueryParams();

        $validation = $this->validator->validate($urlQueryStrings, [
            'page' => 'required|numeric|min:1',
            'perPage' => 'required|numeric|min:1',
        ]);

        if ($validation->fails()) {
            return $this->response(
                $response,
                ['error' => $validation->errors()->firstOfAll()],
                HttpStatusCodes::UNPROCESSABLE_ENTITY
            );
        }

        return $this->response(
            $response,
            [
                'data' => $this->keyService->get(
                    (int) $urlQueryStrings['page'],
                    (int) $urlQueryStrings['perPage']
                ),
            ],
            HttpStatusCodes::OK
        );
    }

    /**
     * Creates new API key.
     */
    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $validation = $this->validator->make(
            $body,
            [
                'name' => 'required|max:255|unique:keys',
            ]
        );

        if ($validation->fails()) {
            return $this->response(
                $response,
                ['error' => $validation->errors()->firstOfAll()],
                HttpStatusCodes::UNPROCESSABLE_ENTITY
            );
        }

        try {
            return $this->response(
                $response,
                ['data' => $this->keyService->create($validation->getValidatedData()['name'])],
                HttpStatusCodes::OK
            );
        } catch (Throwable $e) {
            return $this->response(
                $response,
                ['error' => ErrorMessages::SERVER_ERROR],
                HttpStatusCodes::INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Deletes existing API key
     * $id param can be either Key Name or Key Id in the database.
     *
     * @param mixed $id
     */
    public function delete(Request $request, Response $response, $id): Response
    {
        try {
            $this->keyService->delete(intval($id));

            return $this->response(
                $response,
                null,
                HttpStatusCodes::NO_CONTENT
            );
        } catch (Throwable $e) {
            return $this->response(
                $response,
                ['error' => ErrorMessages::SERVER_ERROR],
                HttpStatusCodes::INTERNAL_SERVER_ERROR
            );
        }
    }
}
