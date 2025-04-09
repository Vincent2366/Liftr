<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the tenant settings.
     */
    public function index()
    {
        return view('tenant.settings');
    }
}