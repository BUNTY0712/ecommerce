<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'StoreCraft'),
            'theme_primary_color' => Setting::get('theme_primary_color', '#4f46e5'),
            'theme_secondary_color' => Setting::get('theme_secondary_color', '#7c3aed'),
            'theme_preset' => Setting::get('theme_preset', 'indigo'),
            'announcement_text' => Setting::get('announcement_text', '🎉 Exclusive Sale: Get 10% OFF with code STORE10'),
            'site_logo' => Setting::get('site_logo'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:100',
            'theme_primary_color' => 'required|string|max:20',
            'theme_secondary_color' => 'nullable|string|max:20',
            'theme_preset' => 'nullable|string|max:50',
            'announcement_text' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        Setting::set('site_name', $request->input('site_name'));
        Setting::set('theme_primary_color', $request->input('theme_primary_color', '#4f46e5'));
        Setting::set('theme_secondary_color', $request->input('theme_secondary_color', '#7c3aed'));
        Setting::set('theme_preset', $request->input('theme_preset', 'custom'));
        Setting::set('announcement_text', $request->input('announcement_text'));

        // Handle logo removal
        if ($request->boolean('remove_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('site_logo', null);
        }

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Site settings & theme updated successfully!');
    }
}
