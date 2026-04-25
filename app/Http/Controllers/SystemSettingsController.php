<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        return view('system_settings.systemSettingsPage', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_phone' => 'nullable|string|max:20',
            'business_email' => 'nullable|email|max:255',
            'business_address' => 'nullable|string',
        ]);

        // Update business settings
        SystemSetting::set('business_name', $request->business_name);
        SystemSetting::set('business_phone', $request->business_phone);
        SystemSetting::set('business_email', $request->business_email);
        SystemSetting::set('business_address', $request->business_address);

        return redirect()->route('system-settings.index')->with('success', 'System settings updated successfully!');
    }
}
