<?php

namespace App\Domains\SuperAdmin\Controllers;

use App\Domains\SuperAdmin\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('super-admin.users.index', compact('users'));
    }
}