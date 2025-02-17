<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Traits\ExceptionHandler;

class SettingController extends Controller
{
    use ExceptionHandler;

    public function edit($type)
    {
        if (!auth()->user()->can('update_settings')) {
            abort(403, 'Unauthorized action.');
        }

        $settings = Setting::all();

        switch ($type) {
            case 'general-settings':
                return view('admin.setting.general', compact('type', 'settings'));
                break;

            case 'email-settings':
                return view('admin.setting.email', compact('type', 'settings'));
                break;

            case 'sms-settings':
                $providers = smsProviders();
                $activeProvider = [
                    'name' => config('SmsCredentials.active_provider'),
                    'data' => smsProviderData(config('SmsCredentials.active_provider'))
                ];

                return view('admin.setting.sms', compact('type', 'settings', 'providers', 'activeProvider'));
                break;

            default:
                return abort(404);
                break;
        }
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('update_settings')) {
            abort(403, 'Unauthorized action.');
        }

        $requests = $request->except('_token');

        if (isset($requests['type']) && $requests['type'] === 'email') {
            $this->updateEmailCredentials($requests);
        }

        foreach ($requests as $key => $value) {
            $data = $value;

            if ($request->hasFile($key)) {
                $existingSetting = Setting::where('key', $key)->first();

                if ($existingSetting) {
                    fileRemove($existingSetting->value);
                }

                $request->validate([
                    $key => 'file|mimes:jpg,jpeg,png,gif,webp|max:2048',
                ]);

                $data = fileUpload($request->file($key), 'media/settings');
            }

            Setting::updateOrCreate(['key' => $key], ['value' => $data]);
        }

        $this->getAlert('success', 'Settings updated successfully.');
        return back();
    }

    /**
     * Update email configuration dynamically.
     */
    private function updateEmailCredentials(array $requests)
    {
        $mailConfig = [
            'transport'  => 'smtp',
            'host'       => $requests['MAIL_HOST'] ?? env('MAIL_HOST', ''),
            'port'       => $requests['MAIL_PORT'] ?? env('MAIL_PORT', ''),
            'encryption' => $requests['MAIL_ENCRYPTION'] ?? env('MAIL_ENCRYPTION', 'tls'),
            'username'   => $requests['MAIL_USERNAME'] ?? env('MAIL_USERNAME', ''),
            'password'   => $requests['MAIL_PASSWORD'] ?? env('MAIL_PASSWORD', ''),
            'timeout'    => null,
            'local_domain' => $requests['MAIL_EHLO_DOMAIN'] ?? env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ];

        $mailFrom = [
            'address' => $requests['MAIL_FROM_ADDRESS'] ?? env('MAIL_FROM_ADDRESS', ''),
            'name'    => $requests['MAIL_FROM_NAME'] ?? env('MAIL_FROM_NAME', ''),
        ];

        config([
            'mail.mailers.smtp' => $mailConfig,
            'mail.from'         => $mailFrom,
        ]);

        $configPath = config_path('mail.php');

        if (file_exists($configPath) && is_writable($configPath)) {
            try {
                $currentConfig = require $configPath;
                $currentConfig['mailers']['smtp'] = $mailConfig;
                $currentConfig['from'] = $mailFrom;

                $newConfig = "<?php\n\nreturn " . var_export($currentConfig, true) . ";\n";
                $newConfig = preg_replace('/=>(\s+)array\s\(/', '=> [', $newConfig);
                $newConfig = str_replace(['array (', ')'], ['[', ']'], $newConfig);

                file_put_contents($configPath, $newConfig);
            } catch (\Exception $e) {
                $this->logException($e);
            }
        }

        app('mail.manager')->forgetMailers();
    }

    private function updateSmsCredentials($activeProvider, $smsProviders)
    {
        // Path to the config file
        $configFilePath = config_path('SmsCredentials.php');

        // Generate the updated content for the config file
        $content = '<?php return ' . var_export([
            'active_provider' => $activeProvider,
            'providers' => $smsProviders,
        ], true) . ';';

        // Write the updated content back to the config file
        File::put($configFilePath, $content);
    }

    public function smsProvider($name)
    {
        $data = smsProviderData($name);
        $keys = [];

        foreach ($data['fields'] as $key => $value) {
            array_push($keys, $key);
        }

        return response()->json([
            'message' => 'Success',
            'fields' => $keys,
            'credentials' => $data['credentials']
        ]);
    }

    public function updateSmsProviders(Request $request)
    {
        if (!auth()->user()->can('update_settings')) {
            abort(403, 'Unauthorized action.');
        }

        $activeProvider = $request->provider;

        $smsProviders = config('SmsCredentials.providers');
        $smsProviders[$activeProvider] = $request->except(['_token', 'provider']);

        $this->updateSmsCredentials($activeProvider, $smsProviders);

        alert('Success!', 'Settings updated successfully.', 'success');
        return back();
    }
}
