<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AdaReachService
{
    protected $client;
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = "https://api.mobireach.com.bd";
    }

    // 1. Get or Generate Token
    public function getToken()
    {
        // Check if token is cached
        if (Cache::has('adareach_token')) {
            return Cache::get('adareach_token');
        }

        // If token is not cached or expired, generate a new one
        return $this->generateToken();
    }

    // 2. Generate and Cache the Token
    public function generateToken()
    {
        try {
            $response = $this->client->post($this->apiUrl . '/auth/tokens', [
                'json' => [
                    'username' => env('SMS_MOBIREACH_USERNAME'),
                    'password' => env('SMS_MOBIREACH_PASSWORD'),
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Cache the token and refresh token for 59 minutes (1 hour minus a buffer)
            Cache::put('adareach_token', $data['token'], now()->addMinutes(59));
            Cache::put('adareach_refresh_token', $data['refresh_token'], now()->addMinutes(59));

            return $data['token'];
        } catch (\Exception $e) {
            Log::error('Failed to generate token: ' . $e->getMessage());
            // throw new \Exception('Could not generate token');
        }
    }

    // 3. Refresh Token
    public function refreshToken()
    {
        try {
            // Get the refresh token from the cache
            $refreshToken = Cache::get('adareach_refresh_token');
            if (!$refreshToken) {
                return $this->generateToken(); // If no refresh token, generate a new token
            }

            // Call the refresh token API
            $response = $this->client->post($this->apiUrl . '/auth/token/refresh', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $refreshToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Cache the new token and refresh token
            Cache::put('adareach_token', $data['token'], now()->addMinutes(59));
            Cache::put('adareach_refresh_token', $data['refresh_token'], now()->addMinutes(59));

            return $data['token'];
        } catch (\Exception $e) {
            Log::error('Failed to refresh token: ' . $e->getMessage());
            return $this->generateToken(); // If refresh fails, generate a new token
        }
    }

    // 4. Send SMS (using the cached or refreshed token)
    public function sendSMS($receiver, $content)
    {
        try {
            // Get the token (either cached or freshly generated)
            $token = $this->getToken();

            // Send the SMS request
            $response = $this->client->post($this->apiUrl . '/sms/send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'sender' => env('SMS_MOBIREACH_SENDER'),
                    'receiver' => [$receiver],
                    'contentType' => 1, // contentType (1=Regular, 2=Unicode)
                    'content' => $content,
                    'msgType' => 'T', // msgType ('T' = transactional, 'P' = promotional)
                    'requestType' => 'S', // requestType ('S' = Single, 'B' = Bulk)
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $data;
        } catch (\Exception $e) {
            // Handle errors (e.g., token expired, request failed)
            Log::error('Failed to send SMS: ' . $e->getMessage());

            // If token is expired, try refreshing the token and retry the SMS request
            if ($e->getCode() === 401) {
                $this->refreshToken();
                return $this->sendSMS($receiver, $content);
            }

            return $e;
        }
    }

    // 5. Check SMS Status (use the cached or refreshed token)
    public function checkSMSStatus($messageId, $receiver)
    {
        try {
            // Get the token (either cached or freshly generated)
            $token = $this->getToken();
            $response = $this->client->get($this->apiUrl . '/sms/status', [
                'query' => [
                    'sender' => env('SMS_MOBIREACH_SENDER'),
                    'messageId' => $messageId,
                    'receiver' => $receiver,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Handle errors (e.g., token expired, request failed)
            Log::error('Failed to check status: ' . $e->getMessage());

            // If token is expired, try refreshing the token and retry the SMS request
            if ($e->getCode() === 401) {
                $this->refreshToken();
                return $this->checkSMSStatus($messageId, $receiver);
            }
        }
    }

    // 6. Check Balance (use the cached or refreshed token)
    public function checkBalance()
    {
        try {
            // Get the token (either cached or freshly generated)
            $response = $this->client->get($this->apiUrl . '/balance', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Handle errors (e.g., token expired, request failed)
            Log::error('Failed to check balance: ' . $e->getMessage());

            // If token is expired, try refreshing the token and retry the SMS request
            if ($e->getCode() === 401) {
                $this->refreshToken();
                return $this->checkBalance();
            }
        }
    }
}
