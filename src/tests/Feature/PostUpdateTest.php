<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_should_update_a_post(): void
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
        
        $updatedBody = [
            'title' => 'Hello Updated',
            'content' => 'World Updated',
        ];
        $updatePostResponse = $this->put('/api/posts/' . $createdPostId, $updatedBody);
        $updatePostResponse->assertJson([
            'post' => ['title' => $updatedBody['title'], 'content' => $updatedBody['content']],
        ]);
        $updatePostResponse->assertStatus(200);

        // Show the updated post
        $showPostResponse = $this->get('/api/posts/' . $createdPostId);
        $showPostResponse->assertJson([
            'post' => ['title' => $updatedBody['title'], 'content' => $updatedBody['content']],
        ]);
        $showPostResponse->assertStatus(200);
    }
}
