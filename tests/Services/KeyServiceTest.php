<?php

namespace BrosSquad\Linker\Api\Tests\Services;

use BrosSquad\Linker\Api\Models\Key;
use BrosSquad\Linker\Api\Services\KeyService;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

$dotenv = Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

/**
 * @internal
 * @coversNothing
 */
class KeyServiceTest extends TestCase
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

    public function testGenKey()
    {
        $keyService = new KeyService();

        ['apiKey' => $apiKey, 'key' => $key] = $keyService->create('Test');
        $this->assertIsString($apiKey);
        $this->assertEquals('BrosSquadLinker.', substr($apiKey, 0, strlen('BrosSquadLinker.')));
        $this->assertEquals(1, Key::query()->count());
        $key->delete();
    }

    public function testVerifyKey()
    {
        $keyService = new KeyService();

        ['apiKey' => $apiKey, 'key' => $key] = $keyService->create('Test');

        $this->assertTrue($keyService->verify($apiKey));
    }
}
