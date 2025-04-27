<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class SessionController extends Controller
{
    /**
     * Display a listing of sessions.
     */
    public function index()
    {
        return view('tenant.sessions.index');
    }

    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        return view('tenant.sessions.create');
    }

    /**
     * Store a newly created session.
     */
    public function store(Request $request)
    {
        // Validation and storage logic
        return redirect()->route('tenant.sessions');
    }

    /**
     * Display the specified session.
     */
    public function show(string $id)
    {
        return view('tenant.sessions.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(string $id)
    {
        return view('tenant.sessions.edit', ['id' => $id]);
    }

    /**
     * Update the specified session.
     */
    public function update(Request $request, string $id)
    {
        // Validation and update logic
        return redirect()->route('tenant.sessions.show', $id);
    }

    /**
     * Remove the specified session.
     */
    public function destroy(string $id)
    {
        // Delete logic
        return redirect()->route('tenant.sessions');
    }

    /**
     * Display the user activity page.
     */
    public function userActivity()
    {
        $user = Auth::user();
        $recentActivities = Activity::where('user_id', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->take(5)
                                   ->get();
        
        return view('tenant.activity.user-activity', compact('recentActivities'));
    }

    /**
     * Start a new activity session for the user.
     */
    public function startActivity(Request $request)
    {
        $user = Auth::user();
        
        // Create a new activity record
        $activity = Activity::create([
            'user_id' => $user->id,
            'start_time' => now(),
            'status' => 'active'
        ]);
        
        return redirect()->route('tenant.user.activity')
            ->with('success', 'Activity session started successfully!');
    }

    /**
     * Display a listing of the activities.
     */
    public function activityIndex()
    {
        $activities = Activity::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
        
        return view('tenant.activity.index', compact('activities'));
    }

    /**
     * Stop the current active activity session for the user.
     */
    public function stopActivity(Request $request)
    {
        $user = Auth::user();
        
        // Find the user's active activity
        $activity = Activity::where('user_id', $user->id)
                      ->where('status', 'active')
                      ->whereNull('end_time')
                      ->latest()
                      ->first();
        
        if ($activity) {
            // Update the activity record
            $activity->update([
                'end_time' => now(),
                'status' => 'completed'
            ]);
            
            return redirect()->route('tenant.user.activity')
                ->with('success', 'Activity session completed successfully!');
        }
        
        return redirect()->route('tenant.user.activity')
            ->with('error', 'No active session found to stop.');
    }

    /**
     * Generate a PDF report of user activities.
     */
    public function generateReport()
    {
        // Verify tenant is on Ultimate plan
        if (tenant()->plan !== \App\Models\Tenant::PLAN_ULTIMATE) {
            return redirect()->back()->with('error', 'Report generation is only available on the Ultimate plan.');
        }
        
        // Get all activities for the report
        $activities = Activity::with('user')
                        ->orderBy('start_time', 'desc')
                        ->get();
        
        // Create PDF instance using our custom class
        $pdf = new \App\Reports\ActivityReportPDF();
        $pdf->AliasNbPages(); // For page numbering with {nb}
        $pdf->AddPage();
        
        // Add tenant name
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 10, 'Tenant: ' . (tenant()->name ?? 'Unknown'), 0, 1, 'R');
        
        // Add summary section
        $pdf->AddSummary($activities);
        
        // Add activities table
        $pdf->AddActivitiesTable($activities);
        
        // Output the PDF
        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="activities-report.pdf"'
        ]);
    }
}






