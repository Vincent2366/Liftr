<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index()
    {
        return view('tenant.clients.index');
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('tenant.clients.create');
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        // Validation and storage logic
        return redirect()->route('tenant.clients');
    }

    /**
     * Display the specified client.
     */
    public function show(string $id)
    {
        return view('tenant.clients.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(string $id)
    {
        return view('tenant.clients.edit', ['id' => $id]);
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, string $id)
    {
        // Validation and update logic
        return redirect()->route('tenant.clients.show', $id);
    }

    /**
     * Remove the specified client.
     */
    public function destroy(string $id)
    {
        // Delete logic
        return redirect()->route('tenant.clients');
    }
}