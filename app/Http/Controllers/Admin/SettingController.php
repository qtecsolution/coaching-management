<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
            case 'general':
                return view('admin.setting.general', compact('type', 'settings'));
                break;

            case 'smtp':
                return view('admin.setting.smtp', compact('type', 'settings'));
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
