<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function test($provider)
    {
        $provider = smsProviderData($provider);

        return $provider;
    }
}
