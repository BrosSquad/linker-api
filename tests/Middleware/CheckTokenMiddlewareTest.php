<?php

namespace BrosSquad\Linker\Api\Tests\Middleware;

use BrosSquad\Linker\Api\Exceptions\AuthenticationTypeException;
use BrosSquad\Linker\Api\Exceptions\InvalidTokenException;
use BrosSquad\Linker\Api\Exceptions\NoTokenSuppliedException;
use BrosSquad\Linker\Api\Services\CheckTokenService;
use BrosSquad\Linker\Api\Services\KeyService;
use BrosSquad\Linker\Api\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class CheckTokenMiddlewareTest extends TestCase
{
    public function testHandlePasses()
    {
        $keyService = new KeyService();
        $checkTokenService = new CheckTokenService($keyService);

        ['apiKey' => $apiKey, 'key' => $key ] = $keyService->create('Test');

        $this->assertEquals($apiKey, $checkTokenService->check(['bearer '.$apiKey]));

        $key->delete();
    }

    public function testHandleFailsNoTokenSuppliedException()
    {
        $this->expectException(NoTokenSuppliedException::class);
        $keyService = new KeyService();
        $checkTokenService = new CheckTokenService($keyService);

        ['apiKey' => $apiKey, 'key' => $key ] = $keyService->create('Test');

        $checkTokenService->check([]);
    }

    public function testHandleFailsAuthenticationTypeException()
    {
        $this->expectException(AuthenticationTypeException::class);
        $keyService = new KeyService();
        $checkTokenService = new CheckTokenService($keyService);

        ['apiKey' => $apiKey, 'key' => $key ] = $keyService->create('Test');

        $checkTokenService->check(['api-key '.$apiKey]);
    }

    public function testHandleFailsSodiumException()
    {
        $this->expectException(InvalidTokenException::class);
        $keyService = new KeyService();
        $checkTokenService = new CheckTokenService($keyService);

        ['apiKey' => $apiKey, 'key' => $key ] = $keyService->create('Test');

        $checkTokenService->check(['bearer BrosSquad.1.hakjsdhuehajsdhkajshd.hasjdhaksjdhkasjdhlajsdhuwhdq8wueiqw']);
    }
}
