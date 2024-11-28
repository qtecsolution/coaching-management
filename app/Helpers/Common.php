<?php

namespace App\Http\Helpers;

use App\Models\Batch;
use App\Models\Payment;
use App\Models\PaymentReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                    'credentials' => config("smsCredentials.providers.{$providerName}", null)
                ];
            }
        }

        return null;
    }
}

if (!function_exists('updatePaymentReport')) {
    function updatePaymentReport($month)
    {
        $report = PaymentReport::firstOrCreate(
            ['month' => $month],
            [
                'estimated_collection_amount' => 0,
                'collected_amount' => 0,
                'due_amount' => 0,
            ]
        );
        $report->estimated_collection_amount = Batch::active()
        ->selectRaw('SUM(total_students * tuition_fee) as total_amount')
        ->pluck('total_amount')
        ->first() ?? 0;
        $report->collected_amount = Payment::where('month', $month)->sum('amount');
        $report->due_amount = $report->estimated_collection_amount - $report->collected_amount;
        $report->save();
    }
}
if (!function_exists('formatSlug')) {
    function formatSlug($slug)
    {
        return ucwords(str_replace('_', ' ', $slug));
    }
}