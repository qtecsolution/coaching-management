<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        try {
            $sms = new SmsController();
            $response = $sms->test('Banglalink');

            return $response;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return response()->json([
            'message' => 'Hello World!',
            'start_url' => route('auth.login.show'),
        ]);
    }
}
