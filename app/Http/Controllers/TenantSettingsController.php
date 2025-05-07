<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantSettingsController extends Controller
{
    /**
     * Show the form for editing tenant settings.
     */
    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        $themes = Tenant::getThemes();
        
        return view('tenant-settings.edit', compact('tenant', 'themes'));
    }

    /**
     * Update the tenant settings.
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'theme' => 'required|string|in:' . implode(',', array_keys(Tenant::getThemes())),
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($tenant->logo && Storage::disk('public')->exists($tenant->logo)) {
                Storage::disk('public')->delete($tenant->logo);
            }
            
            // Store new logo
            $logoPath = $request->file('logo')->store('tenant-logos', 'public');
            $tenant->logo = $logoPath;
        }
        
        // Update theme settings
        $tenant->name = $validated['name'];
        $tenant->theme = $validated['theme'];
        
        // Only set custom colors if theme is custom
        if ($validated['theme'] === Tenant::THEME_CUSTOM) {
            $tenant->primary_color = $validated['primary_color'];
            $tenant->secondary_color = $validated['secondary_color'];
            $tenant->accent_color = $validated['accent_color'];
        } else {
            // Use predefined theme colors
            $themeColors = Tenant::getThemes()[$validated['theme']];
            $tenant->primary_color = $themeColors['primary'];
            $tenant->secondary_color = $themeColors['secondary'];
            $tenant->accent_color = $themeColors['accent'];
        }
        
        $tenant->save();
        
        return redirect()->route('dashboard')->with('success', "Tenant {$tenant->id} settings updated successfully");
    }
}