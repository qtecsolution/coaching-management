<?php

namespace App\Traits;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

trait MailConfig
{
    public function mailConfig()
    {
        $settings = Setting::whereIn('key', [
            'MAIL_HOST',
            'MAIL_PORT',
            'MAIL_ENCRYPTION',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_FROM_ADDRESS',
            'MAIL_FROM_NAME'
        ])->pluck('value', 'key');

        $mailData = [
            'transport'  => 'smtp',
            'host'       => $settings['MAIL_HOST'] ?? '',
            'port'       => $settings['MAIL_PORT'] ?? '',
            'encryption' => $settings['MAIL_ENCRYPTION'] ?? 'tls',
            'username'   => $settings['MAIL_USERNAME'] ?? '',
            'password'   => $settings['MAIL_PASSWORD'] ?? '',
            'from'       => [
                'address' => $settings['MAIL_FROM_ADDRESS'] ?? '',
                'name'    => $settings['MAIL_FROM_NAME'] ?? '',
            ],
        ];

        config(['mail.mailers.smtp' => $mailData]);

        return Mail::mailer('smtp');
    }
}
