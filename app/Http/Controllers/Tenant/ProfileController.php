<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        return view('tenant.profile');
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'profile_picture' => ['nullable', 'image', 'max:1024'],
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }
        
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            try {
                // Get tenant ID
                $tenantId = tenant('id');
                
                // Create file name and path
                $file = $request->file('profile_picture');
                $filename = $file->hashName();
                $path = "profile-pictures/{$filename}";
                
                // Define the correct directory path - avoid duplication
                $directory = storage_path("tenant{$tenantId}/app/public/profile-pictures");
                
                // Debug information
                \Log::info("Uploading profile picture to: {$directory}/{$filename}");
                \Log::info("Tenant ID: {$tenantId}");
                
                // Ensure the directory exists
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Move the uploaded file to the tenant's storage
                if ($file->move($directory, $filename)) {
                    \Log::info("File moved successfully");
                    
                    // Check if the profile_picture column exists
                    if (Schema::hasColumn('users', 'profile_picture')) {
                        // Store just the relative path in the database
                        $user->profile_picture = $path;
                        \Log::info("Profile picture path saved: {$path}");
                    } else {
                        return redirect()->route('tenant.profile')
                            ->with('error', 'Profile picture upload is not available yet. Please run migrations first.');
                    }
                } else {
                    \Log::error("Failed to move file");
                    return redirect()->route('tenant.profile')
                        ->with('error', 'Failed to upload profile picture: Could not move file');
                }
            } catch (\Exception $e) {
                \Log::error("Profile picture upload error: " . $e->getMessage());
                return redirect()->route('tenant.profile')
                    ->with('error', 'Failed to upload profile picture: ' . $e->getMessage());
            }
        }
        
        $user->save();
        
        return redirect()->route('tenant.profile')->with('status', 'profile-updated');
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Rules\Password::defaults(), 'confirmed'],
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('tenant.profile')->with('status', 'password-updated');
    }
}





