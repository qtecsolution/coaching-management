<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ExceptionHandler
{
    public function logException($exception)
    {
        Log::error($exception->getMessage() . ' on line ' . $exception->getLine() . ' in file ' . $exception->getFile());
        return true;
    }

    public function getAlert($type = 'info', $message = 'This is a test message.')
    {
        $title = ($type == 'error') ? 'Oops!' : 'Hey!';

        return alert($title, $message, $type);
    }
}
