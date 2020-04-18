<?php

namespace BrosSquad\Linker\Api\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

/**
 * @internal
 * @coversNothing
 */
class TestCase extends PHPUnitTestCase
{
    /**
     * @var Illuminate\Database\Capsule\Manager
     */
    private $capsule;

    protected function setUp(): void
    {
        $this->capsule = require __DIR__.'/../src/db/db.php';
    }

    public function tearDown(): void
    {
        $this->capsule->table('links')->truncate();
        $this->capsule->table('keys')->truncate();
    }
}
