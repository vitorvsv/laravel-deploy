<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_should_create_a_post(): void
    {
        $body = [
            'title' => 'Hello',
            'content' => 'World',
        ];
        
        $createdPostResponse = $this->post('/api/posts', $body);
        $createdPostResponse->assertJson([
            'post' => ['title' => $body['title'], 'content' => $body['content']],
        ]);
        $createdPostResponse->assertStatus(200);

        $createdPostId = $createdPostResponse->json('post.id');
        
        $showPostResponse = $this->get('/api/posts/' . $createdPostId);
        $showPostResponse->assertJson([
            'post' => ['title' => $body['title'], 'content' => $body['content']],
        ]);
        $showPostResponse->assertStatus(200);
    }
}
