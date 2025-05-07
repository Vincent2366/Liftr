<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display the tenant settings.
     */
    public function index()
    {
        return view('tenant.settings');
    }

    /**
     * Show the theme settings page.
     */
    public function themeSettings()
    {
        // Additional check to ensure only Admin can view theme settings
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'You do not have permission to view theme settings.');
        }
        
        $themeSettings = \App\Models\ThemeSetting::current();
        $availableThemes = \App\Models\ThemeSetting::getThemes();
        
        return view('tenant.settings.theme', [
            'themeSettings' => $themeSettings,
            'availableThemes' => $availableThemes
        ]);
    }

    /**
     * Update the theme settings.
     */
    public function updateThemeSettings(Request $request)
    {
        // Additional check to ensure only Admin can update themes
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'You do not have permission to modify theme settings.');
        }
        
        $themes = array_keys(\App\Models\ThemeSetting::getThemes());
        
        $validated = $request->validate([
            'theme' => 'required|string|in:' . implode(',', $themes),
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $themeSettings = \App\Models\ThemeSetting::current();
        $themeSettings->theme = $validated['theme'];
        
        // Only set custom colors if theme is custom
        if ($validated['theme'] === \App\Models\ThemeSetting::THEME_CUSTOM) {
            $themeSettings->primary_color = $validated['primary_color'];
            $themeSettings->secondary_color = $validated['secondary_color'];
            $themeSettings->accent_color = $validated['accent_color'];
        } else {
            // Use predefined theme colors
            $themeColors = \App\Models\ThemeSetting::getThemes()[$validated['theme']];
            $themeSettings->primary_color = $themeColors['primary'];
            $themeSettings->secondary_color = $themeColors['secondary'];
            $themeSettings->accent_color = $themeColors['accent'];
        }
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $themeSettings->logo_path = $logoPath;
        }
        
        $themeSettings->save();
        
        return redirect()->route('tenant.theme.settings')
            ->with('success', 'Theme settings updated successfully.');
    }
}





