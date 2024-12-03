<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
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
                    'name' => config('smsCredentials.active_provider'),
                    'data' => smsProviderData(config('smsCredentials.active_provider'))
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
        foreach ($requests as $key => $value) {
            $data = $value;

            if ($request->hasFile($key)) {
                if (Setting::where('key', $key)->exists()) {
                    fileRemove(Setting::where('key', $key)->first()->value);
                }

                $request->validate([
                    $key => 'file|mimes:jpg,jpeg,png,gif,webp|max:2048',
                ]);

                $data = fileUpload($request->file($key), 'media/settings');
            }

            Setting::updateOrCreate(['key' => $key], ['value' => $data]);
        }

        alert('Success!', 'Settings updated successfully.', 'success');
        return back();
    }

    private function updateSmsCredentials($activeProvider, $smsProviders)
    {
        // Path to the config file
        $configFilePath = config_path('smsCredentials.php');

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

        $smsProviders = config('smsCredentials.providers');
        $smsProviders[$activeProvider] = $request->except(['_token', 'provider']);

        $this->updateSmsCredentials($activeProvider, $smsProviders);

        alert('Success!', 'Settings updated successfully.', 'success');
        return back();
    }
}
