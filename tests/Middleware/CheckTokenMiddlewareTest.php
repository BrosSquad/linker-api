<?php

namespace BrosSquad\Linker\Api\Tests\Middleware;

use BrosSquad\Linker\Api\Exceptions\AuthenticationTypeException;
use BrosSquad\Linker\Api\Exceptions\NoTokenSuppliedException;
use BrosSquad\Linker\Api\Services\CheckTokenService;
use BrosSquad\Linker\Api\Services\KeyService;
use PHPUnit\Framework\TestCase;
use SodiumException;

/**
 * @internal
 * @coversNothing
 */
class CheckTokenMiddlewareTest extends TestCase
{
    /**
     * @var Illuminate\Database\Capsule\Manager
     */
    private $capsule;

    protected function setUp(): void
    {
        // @var Illuminate\Database\Capsule\Manager
        $this->capsule = require __DIR__.'/../../src/db/db.php';
    }

    public function tearDown(): void
    {
        $this->capsule->table('keys')->truncate();
    }

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
        $this->expectException(SodiumException::class);
        $keyService = new KeyService();
        $checkTokenService = new CheckTokenService($keyService);

        ['apiKey' => $apiKey, 'key' => $key ] = $keyService->create('Test');

        $checkTokenService->check(['bearer BrosSquad.1.hakjsdhuehajsdhkajshd.hasjdhaksjdhkasjdhlajsdhuwhdq8wueiqw']);
    }
}
