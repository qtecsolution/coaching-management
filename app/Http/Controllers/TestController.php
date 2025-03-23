<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'message' => 'Hello World!',
            'start_url' => route('auth.login.show'),
        ]);
    }
}
