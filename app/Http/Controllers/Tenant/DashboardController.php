<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'Admin') {
            // Get monthly user count
            $userCount = \App\Models\User::whereMonth('created_at', now()->month)->count();
            
            // Get client/session count
            $clientCount = \App\Models\Appointment::count();
            
            // Get recent appointments
            $appointments = \App\Models\Appointment::with('user')
                                    ->orderBy('appointment_date', 'desc')
                                    ->orderBy('appointment_time', 'desc')
                                    ->take(10)
                                    ->get();
            
            return view('tenant.dashboard.tenantDashboard', [
                'userCount' => $userCount,
                'clientCount' => $clientCount,
                'appointments' => $appointments
            ]);
        }
        
        // Redirect regular users to their dashboard
        return redirect()->route('tenant.user.dashboard');
    }
    
    /**
     * Display the dashboard for regular users.
     *
     * @return \Illuminate\View\View
     */
    public function userDashboard()
    {
        return view('tenant.dashboard.userDashboard', [
            'user' => Auth::user(),
        ]);
    }
}


