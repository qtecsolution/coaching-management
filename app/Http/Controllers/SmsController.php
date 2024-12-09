<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Xenon\LaravelBDSms\Sender;

class SmsController extends Controller
{
    public function testSms($provider)
    {
        try {
            $providerData = smsProviderData($provider);

            if (!is_null($providerData)) {
                $config = config('smsCredentials.providers')[$provider];
                $response = $this->sendSms($provider, $config, '1234567890', 'Test SMS');
                return response()->json($response, 200);
            } else {
                throw new \Exception('Provider not found');
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function sendSms($provider, $config, $mobile, $message)
    {
        try {
            $providerClass = "Xenon\LaravelBDSms\Provider\\" . $provider;

            $sender = Sender::getInstance();
            $sender->setProvider($providerClass);
            $sender->setMobile($mobile);
            $sender->setMessage($message);
            $sender->setConfig($config);
            $status = $sender->send();

            return $status;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
