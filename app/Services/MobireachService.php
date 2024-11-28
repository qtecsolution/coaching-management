<?php
/*
 *  Last Modified: 11/28/24, 02:00 PM
 *  Copyright (c) 2024
 *  - Modified by Md Muhaiminul Islam Shihab
 *  - For inquiries, contact: https://linkedin.com/in/muhaiminshihab
 */

namespace Xenon\LaravelBDSms\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Xenon\LaravelBDSms\Handler\ParameterException;
use Xenon\LaravelBDSms\Request;
use Xenon\LaravelBDSms\Sender;

class Mobireach extends AbstractProvider
{
    private string $apiEndpoint = "https://api.mobireach.com.bd";
    private Client $httpClient;

    /**
     * MobiReach constructor.
     */
    public function __construct(Sender $sender)
    {
        $this->senderObject = $sender;
        $this->httpClient = new Client();
    }

    /**
     * Send SMS through the MobiReach API.
     *
     * @param string $receiver
     * @param string $content
     * @return bool
     * @throws GuzzleException
     */
    public function sendRequest()
    {
        $token = $this->getToken();
        $number = $this->senderObject->getMobile();
        $text = $this->senderObject->getMessage();
        $config = $this->senderObject->getConfig();
        $queue = $this->senderObject->getQueue();
        $queueName = $this->senderObject->getQueueName();
        $tries = $this->senderObject->getTries();
        $backoff = $this->senderObject->getBackoff();

        $payload = [
            'sender' => $config['sender'],
            'receiver' => $number,
            'contentType' => $config['contentType'],
            'content' => $text,
            'msgType' => $config['msgType'],
            'requestType' => $config['requestType'],
        ];

        $response = $this->httpClient->post($this->apiEndpoint . '/sms/send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'sender' => $config['sender'],
                'receiver' => $number,
                'contentType' => $config['contentType'],
                'content' => $text,
                'msgType' => $config['msgType'],
                'requestType' => $config['requestType'],
            ],
        ]);

        if ($queue) {
            return true;
        }

        $body = $response->getBody();
        $smsResult = $body->getContents();

        $data['number'] = $number;
        $data['message'] = $text;

        return $this->generateReport($smsResult, $data)->getContent();
    }

    /**
     * Get or generate the token for MobiReach API.
     * @return string
     * @throws GuzzleException
     */
    private function getToken(): string
    {
        if (Cache::has('mobireach_token')) {
            return Cache::get('mobireach_token');
        }

        return $this->generateToken();
    }

    /**
     * Generate a new token for MobiReach API.
     * @return string
     * @throws GuzzleException
     */
    private function generateToken(): string
    {
        $config = $this->senderObject->getConfig();

        $response = $this->httpClient->post("{$this->apiEndpoint}/auth/tokens", [
            'json' => [
                'username' => $config['username'],
                'password' => $config['password'],
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        Cache::put('mobireach_token', $data['token'], now()->addMinutes(59));
        Cache::put('mobireach_refresh_token', $data['refresh_token'], now()->addMinutes(59));

        return $data['token'];
    }

    /**
     * Refresh the token for MobiReach API.
     * @return string
     * @throws GuzzleException
     */
    private function refreshToken(): string
    {
        $refreshToken = Cache::get('mobireach_refresh_token');

        if (!$refreshToken) {
            return $this->generateToken();
        }

        $response = $this->httpClient->post("{$this->apiEndpoint}/auth/token/refresh", [
            'headers' => [
                'Authorization' => "Bearer {$refreshToken}",
                'Content-Type' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        Cache::put('mobireach_token', $data['token'], now()->addMinutes(59));
        Cache::put('mobireach_refresh_token', $data['refresh_token'], now()->addMinutes(59));

        return $data['token'];
    }

    /**
     * Handle parameter exceptions.
     */
    public function errorException()
    {
        if (!array_key_exists('username', $this->senderObject->getConfig())) {
            throw new ParameterException('username is absent in configuration');
        }

        if (!array_key_exists('password', $this->senderObject->getConfig())) {
            throw new ParameterException('password is absent in configuration');
        }

        if (!array_key_exists('sender', $this->senderObject->getConfig())) {
            throw new ParameterException('sender is absent in configuration');
        }

        if (!array_key_exists('contentType', $this->senderObject->getConfig())) {
            throw new ParameterException('contentType is absent in configuration');
        }

        if (!array_key_exists('msgType', $this->senderObject->getConfig())) {
            throw new ParameterException('msgType is absent in configuration');
        }

        if (!array_key_exists('requestType', $this->senderObject->getConfig())) {
            throw new ParameterException('requestType is absent in configuration');
        }
    }
}
