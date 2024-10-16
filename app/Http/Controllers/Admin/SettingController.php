<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    protected function setEnvValue($key, $value)
    {
        $path = base_path('.env');

        // Check if the .env file exists
        if (File::exists($path)) {
            // Get the contents of the .env file
            $envContent = File::get($path);

            // Use a regular expression to replace the existing value or add the key-value pair
            $pattern = "/^{$key}=(.*)$/m";
            if (preg_match($pattern, $envContent)) {
                // Update existing key-value pair
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                // If the key doesn't exist, add it to the file
                $envContent .= "\n{$key}={$value}\n";
            }

            // Write the updated content back to the .env file
            File::put($path, $envContent);

            // Optionally clear the cached config values (since env values are cached)
            if (function_exists('config')) {
                // Refreshing the config cache
                Artisan::call('optimize:clear');
            }
        }
    }

    public function edit($type)
    {
        switch ($type) {
            case 'general':
                return view('admin.setting.general', compact('type'));
                break;

            case 'smtp':
                return view('admin.setting.smtp', compact('type'));
                break;

            default:
                return abort(404);
                break;
        }
    }

    public function update(Request $request)
    {
        $requests = $request->except('_token');
        foreach ($requests as $key => $value) {
            $this->setEnvValue($key, '"' . $value . '"');
        }

        toast('Settings updated successfully.', 'success');
        return back();
    }
}
