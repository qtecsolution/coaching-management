<?php

namespace App\Http\Controllers;

use App\Services\AdaReachService;
use Illuminate\Http\Request;
use Xenon\LaravelBDSms\Provider\Mobireach;
use Xenon\LaravelBDSms\Sender;

class TestController extends Controller
{
    public function index(Request $request)
    {
        // $path = app_path('../');
        // $output = shell_exec('ls -la ' . escapeshellarg($path));
        // dd($output);

        return response()->json([
            'message' => 'Hello World!',
            'start_url' => route('auth.login.show'),
        ]);
    }
}
