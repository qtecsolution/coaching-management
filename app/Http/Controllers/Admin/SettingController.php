<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use LaravelBDSms, SMS;

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
                return view('admin.setting.sms-smtp', compact('type', 'settings'));
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
}
