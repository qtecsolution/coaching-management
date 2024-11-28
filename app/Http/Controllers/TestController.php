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
        // try {
        //     $sms = new SmsController();
        //     $response = $sms->test('Banglalink');

        //     return $response;
        // } catch (\Throwable $th) {
        //     return $th->getMessage();
        // }

        // $sender = Sender::getInstance();
        // $sender->setProvider(Mobireach::class);
        // $sender->setMobile(['01829004863']);
        // $sender->setMessage('Hello World!');
        // $sender->setConfig(
        //     [
        //         'username' => env('SMS_MOBIREACH_USERNAME'),
        //         'password' => env('SMS_MOBIREACH_PASSWORD'),
        //         'sender' => env('SMS_MOBIREACH_SENDER'),
        //         'contentType' => env('SMS_MOBIREACH_CONTENT_TYPE'),
        //         'msgType' => env('SMS_MOBIREACH_MSG_TYPE'),
        //         'requestType' => env('SMS_MOBIREACH_REQUEST_TYPE'),
        //     ],
        // );
        // $status = $sender->send();
        // return $status;

        // try {
        //     $smsService = new AdaReachService();
        //     return $smsService->sendSMS('01829004863', 'Hello World!');
        // } catch (\Throwable $th) {
        //     return $th->getMessage();
        // }

        return response()->json([
            'message' => 'Hello World!',
            'start_url' => route('auth.login.show'),
        ]);
    }
}
