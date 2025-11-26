<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        return view('system_settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_phone' => 'nullable|string|max:20',
            'business_email' => 'nullable|email|max:255',
            'business_address' => 'nullable|string',
            'gcash_number' => 'required|string|max:20',
            'gcash_name' => 'required|string|max:255',
            'down_payment_percentage' => 'required|numeric|min:0|max:100',
            'gcash_qr_image_file' => 'nullable|image|max:2048',
        ]);

        // Update business settings
        SystemSetting::set('business_name', $request->business_name);
        SystemSetting::set('business_phone', $request->business_phone);
        SystemSetting::set('business_email', $request->business_email);
        SystemSetting::set('business_address', $request->business_address);

        // Update payment settings
        SystemSetting::set('gcash_number', $request->gcash_number);
        SystemSetting::set('gcash_name', $request->gcash_name);
        SystemSetting::set('down_payment_percentage', $request->down_payment_percentage);

        // Handle QR code image upload
        if ($request->hasFile('gcash_qr_image_file')) {
            // Delete old QR image if it exists and is not the default
            $oldQrPath = SystemSetting::get('gcash_qr_image');
            if ($oldQrPath && $oldQrPath !== 'img/Sample QR.svg' && Storage::disk('public')->exists($oldQrPath)) {
                Storage::disk('public')->delete($oldQrPath);
            }

            // Store new QR image
            $path = $request->file('gcash_qr_image_file')->store('qr-codes', 'public');
            SystemSetting::set('gcash_qr_image', $path);
        }

        return redirect()->route('system-settings.index')->with('success', 'System settings updated successfully!');
    }
}
