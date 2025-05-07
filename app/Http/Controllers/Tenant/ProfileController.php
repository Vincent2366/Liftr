<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
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
                // Store the file and update the user's profile picture path
                $path = $request->file('profile_picture')->store('profile-pictures', 'public');
                
                // Check if the profile_picture column exists
                if (Schema::hasColumn('users', 'profile_picture')) {
                    $user->profile_picture = $path;
                } else {
                    // If the column doesn't exist, we can store it in the user's data attribute
                    // or just skip this part until the migration is run
                    return redirect()->route('tenant.profile')
                        ->with('error', 'Profile picture upload is not available yet. Please run migrations first.');
                }
            } catch (\Exception $e) {
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

