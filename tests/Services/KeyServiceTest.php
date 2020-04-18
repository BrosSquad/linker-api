<?php

namespace BrosSquad\Linker\Api\Tests\Services;

use BrosSquad\Linker\Api\Models\Key;
use BrosSquad\Linker\Api\Services\KeyService;
use BrosSquad\Linker\Api\Tests\TestCase;
use Exception;

/**
 * @internal
 * @coversNothing
 */
class KeyServiceTest extends TestCase
{
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

        $key->delete();
    }

    public function testDeleteKeyWithId()
    {
        $keyService = new KeyService();
        ['key' => $key] = $keyService->create('Test 1');

        $this->assertTrue($keyService->delete($key->id));
    }

    public function testDeleteKeyWithIdNotFound()
    {
        $this->expectException(Exception::class);
        $keyService = new KeyService();
        $keyService->create('Test 1');

        $keyService->delete(5000);
    }

    public function testDeleteKeyWithNameNotFound()
    {
        $this->expectException(Exception::class);
        $keyService = new KeyService();
        $keyService->create('Test 1');

        $keyService->delete('Key that doesnt exist');
    }

    public function testDeleteKeyWithName()
    {
        $name = 'Test 1';
        $keyService = new KeyService();
        ['key' => $key] = $keyService->create($name);

        $this->assertTrue($keyService->delete($name));
    }
}
