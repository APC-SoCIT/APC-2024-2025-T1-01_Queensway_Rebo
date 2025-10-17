<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DatabaseConnectionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_uses_the_testing_database_connection(): void
    {
        // Check which database connection is active
        $connection = config('database.default');
        dump('Current DB Connection: ' . $connection);

        // Run a simple query to confirm DB works
        $result = DB::select('select sqlite_version() as version');

        // Assert we’re actually using SQLite (testing DB)
        $this->assertNotEmpty($result);
        dump('SQLite version: ' . $result[0]->version);

        $this->assertTrue(true);
    }
}
