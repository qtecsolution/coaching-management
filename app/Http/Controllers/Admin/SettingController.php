<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use LaravelBDSms, SMS;

use function App\Http\Helpers\smsProviders;
use function App\Http\Helpers\smsProviderData;

class SettingController extends Controller
{
    public function edit($type)
    {
        if (!auth()->user()->can('update_settings')) {
            abort(403, 'Unauthorized action.');
        }

        $settings = Setting::all();

        switch ($type) {
            case 'general':
                return view('admin.setting.general', compact('type', 'settings'));
                break;

            case 'email-smtp':
                return view('admin.setting.email-smtp', compact('type', 'settings'));
                break;

            case 'sms-smtp':
                $providers = smsProviders();

                return view('admin.setting.sms-smtp', compact('type', 'settings', 'providers'));
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
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        alert('Yahoo!', 'Settings updated successfully.', 'success');
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

    public function provider()
    {
        $data = smsProviderData(request('provider'));
        $keys = [];

        foreach ($data as $key => $value) {
            array_push($keys, $key);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $keys
        ]);
    }
}
