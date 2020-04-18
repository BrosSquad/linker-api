<?php


namespace BrosSquad\Linker\Api\Controllers;


use Throwable;
use Rakit\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use BrosSquad\Linker\Api\Services\KeyService;

/**
 * Class KeysController
 *
 * @package BrosSquad\Linker\Api\Controllers
 */
class KeysController extends ApiController
{
    /**
     * @var \BrosSquad\Linker\Api\Services\KeyService
     */
    protected KeyService $keyService;
    /**
     * @var \Rakit\Validation\Validator
     */
    protected Validator $validator;

    public function __construct(KeyService $keyService, Validator $validator)
    {
        $this->keyService = $keyService;
        $this->validator = $validator;
    }

    /**
     * Gets all keys from database
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(Request $request, Response $response): Response
    {
        return $this->response($response, ['data' => $this->keyService->get()], 200);
    }

    /**
     * Creates new API key
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return \Psr\Http\Message\ResponseInterface
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
            return $this->response($response, ['errors' => $validation->errors()->firstOfAll()], 422);
        }

        try {
            return $this->response(
                $response,
                ['data' => $this->keyService->create($validation->getValidatedData()['name'])],
                200
            );
        } catch (Throwable $e) {
            return $this->response($response, ['message' => 'An error has occurred'], 500);
        }
    }

    /**
     * Deletes existing API key
     * $id param can be either Key Name or Key Id in the database
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @param $params
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(Request $request, Response $response, $params): Response
    {
        try {
            $this->keyService->delete($params['id']);
            return $this->response($response, null, 204);
        } catch (Throwable $e) {
            return $this->response($response, ['message' => 'An error has occurred'], 500);
        }
    }
}

