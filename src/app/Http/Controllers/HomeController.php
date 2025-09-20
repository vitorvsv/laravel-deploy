<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return json_encode(['message' => 'Home route']);
    }
}