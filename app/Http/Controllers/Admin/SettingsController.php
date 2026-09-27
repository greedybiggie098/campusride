<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const DEFAULTS = [
        'app_name' => 'CampusRide',
        'base_fare' => '2.50',
        'priority_fare' => '3.50',
        'contact_email' => 'dispatch@campusride.edu',
        'contact_phone' => '(555) CAMPUS-RIDE',
        'operating_hours' => 'Day Shuttle: Mon - Fri, 6:30 AM - 8:00 PM | Night Owl: Daily, 8:00 PM - 3:30 AM',
    ];

    public function edit(): View
    {
        $settings = collect(self::DEFAULTS)->mapWithKeys(
            fn ($default, $key) => [$key => AppSetting::getValue($key, $default)]
        );

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'base_fare' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'priority_fare' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'operating_hours' => ['required', 'string', 'max:500'],
        ]);

        foreach ($data as $key => $value) {
            AppSetting::setValue($key, $value);
        }

        return back()->with('success', 'Application settings updated successfully.');
    }
}
