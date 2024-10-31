<?php

namespace App\Http\Helpers;

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