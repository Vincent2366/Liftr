<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = \App\Models\User::all();
        return view('tenant.dashboard.users', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('tenant.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        // Validation and storage logic
        return redirect()->route('tenant.users');
    }

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        return view('tenant.users.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(string $id)
    {
        return view('tenant.users.edit', ['id' => $id]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, string $id)
    {
        // Validation and update logic
        return redirect()->route('tenant.users.show', $id);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(string $id)
    {
        // Delete logic
        return redirect()->route('tenant.users');
    }
}

