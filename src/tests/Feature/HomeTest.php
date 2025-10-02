<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_should_return_hello_from_kubernetes(): void
    {
        $response = $this->get('/');

        $response->assertJson([
            'message' => 'Hello from Kubernetes!',
        ]);

        $response->assertStatus(200);
    }
}
