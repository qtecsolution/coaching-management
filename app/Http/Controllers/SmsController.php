<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Xenon\LaravelBDSms\Sender;

class SmsController extends Controller
{
    public function test($provider)
    {
        $provider = smsProviderData($provider);

        return $provider;
    }

    private function sendSms($provider, $config, $mobile, $message)
    {
        $providerClass = "Xenon\LaravelBDSms\Provider\\" . $provider;

        $sender = Sender::getInstance();
        $sender->setProvider($providerClass);
        $sender->setMobile('017XXYYZZAA');
        $sender->setMessage('helloooooooo boss!');
        $sender->setConfig($config);
        $status = $sender->send();
    }
}
