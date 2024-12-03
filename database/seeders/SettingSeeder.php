<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'APP_NAME' => 'Coaching App',
            'APP_URL' => 'http://localhost:8000',
            'CONTACT_NUMBER' => '1234567890',
            'CONTACT_EMAIL' => 'contact@example.com',
            'CONTACT_ADDRESS' => '123 Main Street, City, Country',
            'FACEBOOK_LINK' => 'https://www.facebook.com',
            'TWITTER_LINK' => 'https://www.twitter.com',
            'LINKEDIN_LINK' => 'https://www.linkedin.com',
            'YOUTUBE_LINK' => 'https://www.youtube.com',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
