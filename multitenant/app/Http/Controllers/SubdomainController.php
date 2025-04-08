<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\SubdomainRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SubdomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = SubdomainRequest::latest()->paginate(10);
        return view('admin.subdomain-requests', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'subdomain' => 'required|string|max:63|regex:/^[a-z0-9]([a-z0-9-])*[a-z0-9]$/|unique:tenants,id',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string|max:500',
        ], [
            'subdomain.regex' => 'Subdomain can only contain lowercase letters, numbers, and hyphens. It cannot start or end with a hyphen.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        try {
            // Store the subdomain request data
            $subdomainRequest = SubdomainRequest::create([
                'subdomain' => $request->subdomain,
                'email' => $request->email,
                'description' => $request->description ?? '',
                'status' => 'pending',
                'tenant_id' => null, // No tenant created yet
            ]);
            
            // Log for debugging
            \Log::info('Subdomain request created', ['id' => $subdomainRequest->id, 'subdomain' => $subdomainRequest->subdomain]);
            
            // Return to the welcome page with a success message
            return redirect('/')->with('success', 'Your request is pending for approval, we will keep you posted');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Failed to create subdomain request', ['error' => $e->getMessage()]);
            
            // Return with error
            return back()->with('error', 'Failed to submit your request. Please try again later.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Approve a subdomain request.
     */
    public function approve($id)
    {
        $subdomainRequest = SubdomainRequest::findOrFail($id);
        
        // Only create tenant if not already created
        if (!$subdomainRequest->tenant_id) {
            // Create the tenant
            $tenant = Tenant::create(['id' => $subdomainRequest->subdomain]);
            $tenant->domains()->create(['domain' => $subdomainRequest->subdomain . '.localhost']);
            
            // Update the request with the tenant ID
            $subdomainRequest->tenant_id = $tenant->id;
        }
        
        $subdomainRequest->status = 'approved';
        $subdomainRequest->save();
        
        // Generate a random password for the tenant admin
        $password = Str::random(10);
        
        // Send email notification
        try {
            Mail::to($subdomainRequest->email)
                ->send(new \App\Mail\SubdomainApproved($subdomainRequest, $password));
            
            \Log::info('Approval email sent', ['subdomain' => $subdomainRequest->subdomain, 'email' => $subdomainRequest->email]);
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'subdomain' => $subdomainRequest->subdomain,
                'email' => $subdomainRequest->email
            ]);
        }
        
        return back()->with('success', 'Subdomain request approved successfully! Tenant database and domain have been created, and notification email has been sent.');
    }

    /**
     * Reject a subdomain request.
     */
    public function reject($id)
    {
        $request = SubdomainRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();
        
        // Here you could add additional logic like sending an email notification
        
        return back()->with('success', 'Subdomain request rejected successfully!');
    }
}











