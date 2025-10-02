<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return json_encode(['posts' => $posts]);
    }

    public function create(Request $request)
    {
        $post = Post::create($request->all());
        return json_encode(['post' => $post]);
    }

    public function show($id)
    {
        $post = Post::find($id);
        return json_encode(['post' => $post]);
    }

    public function delete($id)
    {
        $post = Post::find($id);
        $post->delete();
        return json_encode(['message' => 'Post deleted']);
    }
}