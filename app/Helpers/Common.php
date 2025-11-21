<?php

use App\Models\Batch;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

if (!function_exists('fileUpload')) {
    function fileUpload($file, $directory, $disk = 'public', $fileName = null)
    {
        // Generate a unique file name if not provided
        $fileName = $fileName ?: time() . uniqid('', true) . '.' . $file->getClientOriginalExtension();

        // Store the file on the disk
        $uploadedAt = Storage::disk($disk)->putFileAs($directory, $file, $fileName);

        return $uploadedAt;
    }
}

if (!function_exists('fileRemove')) {
    function fileRemove($path)
    {
        if ($path == null || $path == '') {
            return false;
        }

        $absolutePath = storage_path() . '/app/public/' . $path;

        if (file_exists($absolutePath)) {
            unlink($absolutePath);

            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('absolutePath')) {
    function absolutePath($path)
    {
        if ($path == null || $path == '') {
            return asset('assets/img/default.jpg');
        }

        if (Str::startsWith($path, 'media/')) {
            $absolutePath = storage_path() . '/app/public/' . $path;
            if (file_exists($absolutePath)) {
                return asset('storage/' . $path);
            } else {
                return asset('assets/img/default.jpg');
            }
        } else {
            return $path;
        }
    }
}

if (!function_exists('smsProviders')) {
    function smsProviders()
    {
        $smsProviders = config('sms.providers');
        $filteredProviders = [];

        foreach ($smsProviders as $key => $smsProvider) {
            $providerName = substr($key, strrpos($key, '\\') + 1);

            if (!in_array($providerName, ['CustomGateway', 'DnsBd'])) {
                array_push($filteredProviders, $providerName);
            }
        }

        return $filteredProviders;
    }
}

if (!function_exists('smsProviderData')) {
    function smsProviderData($providerName)
    {
        $providers = config('sms.providers');

        if (array_key_exists($providerName, $providers)) {
            return $providers[$providerName];
        }

        foreach ($providers as $key => $value) {
            if (class_basename($key) === $providerName) {
                return [
                    'fields' => $value,
                    'credentials' => config("SmsCredentials.providers.{$providerName}", null)
                ];
            }
        }

        return null;
    }
}

if (!function_exists('formatSlug')) {
    function formatSlug($slug)
    {
        return ucwords(str_replace('_', ' ', $slug));
    }
}

if (!function_exists('isDemoAccount')) {
    function isDemoAccount($phone = null)
    {
        if (is_null($phone)) {
            $phone = Auth::user()->phone;
        }
        if ($phone == env('DEMO_ADMIN') || $phone == env('DEMO_STUDENT') || $phone == env('DEMO_TEACHER')) {
            return true;
        }
        return false;
    }
}
