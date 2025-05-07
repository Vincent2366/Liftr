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
        $currentVersion = $this->getCurrentVersion();
        $availableVersions = $this->getAvailableVersions();
        
        return view('tenant.settings', [
            'currentVersion' => $currentVersion,
            'availableVersions' => $availableVersions
        ]);
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

    /**
     * Get the current application version from GitHub
     */
    private function getCurrentVersion()
    {
        // In production, you might want to cache this or store in database
        // For now, we'll use the session value or default to '1.0'
        return session('app_version', '1.0');
    }

    /**
     * Get available versions from GitHub
     */
    private function getAvailableVersions()
    {
        // In a real implementation, you would fetch this from GitHub API
        // For example: GET /repos/{owner}/{repo}/releases
        
        // For now, we'll return hardcoded versions
        return [
            '1.0' => [
                'name' => 'v1.0',
                'published_at' => '43 minutes ago',
                'is_latest' => true
            ],
            '1.1' => [
                'name' => 'v1.1',
                'published_at' => null,
                'is_latest' => false,
                'is_prerelease' => true
            ]
        ];
    }

    /**
     * Update to a newer version.
     */
    public function updateVersion(Request $request)
    {
        // Additional check to ensure only Admin can update version
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'You do not have permission to update version.');
        }
        
        $version = $request->input('version');
        
        // Store the version in session to simulate version change
        session(['app_version' => $version]);
        
        // Here you would implement the actual version upgrade logic
        // This could involve pulling the new code from GitHub, running migrations, etc.
        
        // Log the version change
        \Log::info('Tenant version upgraded', [
            'tenant' => tenant()->id,
            'new_version' => $version,
            'user_id' => Auth::id()
        ]);
        
        return redirect()->route('tenant.settings')
            ->with('success', "Successfully upgraded to version {$version}");
    }

    /**
     * Rollback to a previous version.
     */
    public function rollbackVersion(Request $request)
    {
        // Additional check to ensure only Admin can rollback version
        if (Auth::user()->role !== 'Admin') {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'You do not have permission to rollback version.');
        }
        
        $version = $request->input('version');
        
        // Store the version in session to simulate version change
        session(['app_version' => $version]);
        
        // Here you would implement the actual version rollback logic
        // This could involve reverting migrations, restoring settings, etc.
        
        // Log the version change
        \Log::info('Tenant version rolled back', [
            'tenant' => tenant()->id,
            'previous_version' => $version,
            'user_id' => Auth::id()
        ]);
        
        return redirect()->route('tenant.settings')
            ->with('success', "Successfully rolled back to version {$version}");
    }
}



