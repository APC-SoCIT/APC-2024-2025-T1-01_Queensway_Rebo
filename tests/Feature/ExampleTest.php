<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // ✅ add this line
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // ✅ add this line

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
