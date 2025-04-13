<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userCount = \App\Models\User::whereMonth('created_at', now()->month)->count();
        
        return view('tenant.dashboard.tenantDashboard', [
            'userCount' => $userCount,
            // other variables...
        ]);
    }
}
