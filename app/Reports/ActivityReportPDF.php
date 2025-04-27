<?php

namespace App\Reports;

use FPDF;

class ActivityReportPDF extends FPDF
{
    // Page header
    function Header()
    {
        // Set colors
        $headerColor = [41, 128, 185]; // Blue
        
        // Header background
        $this->SetFillColor($headerColor[0], $headerColor[1], $headerColor[2]);
        $this->Rect(0, 0, $this->GetPageWidth(), 40, 'F');
        
        // Logo - uncomment and adjust path when you have a logo
        // $this->Image(public_path('images/logo.png'), 10, 10, 30);
        
        // Title
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 24);
        $this->Cell(0, 25, 'User Activities Report', 0, 1, 'C');
        
        // Tenant info and date
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Generated: ' . now()->format('Y-m-d H:i'), 0, 1, 'R');
        
        // Line break after header
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Ln(5);
        $this->Cell(0, 5, 'Copyright © ' . date('Y') . ' ' . (tenant()->name ?? 'LIFTR'), 0, 0, 'C');
    }
    
    // Add tenant summary section
    function AddSummary($activities)
    {
        $textColor = [44, 62, 80]; // Dark blue/gray
        
        $this->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Activity Summary', 0, 1, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 8, 'Total Activities: ' . $activities->count(), 0, 1, 'L');
        $this->Cell(0, 8, 'Active Sessions: ' . $activities->where('status', 'active')->count(), 0, 1, 'L');
        $this->Cell(0, 8, 'Completed Sessions: ' . $activities->where('status', 'completed')->count(), 0, 1, 'L');
        $this->Ln(10);
    }
    
    // Add activities table
    function AddActivitiesTable($activities)
    {
        $accentColor = [52, 152, 219]; // Lighter blue
        $textColor = [44, 62, 80]; // Dark blue/gray
        
        // Table header
        $this->SetFillColor($accentColor[0], $accentColor[1], $accentColor[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(50, 10, 'User', 1, 0, 'C', true);
        $this->Cell(35, 10, 'Date', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Start Time', 1, 0, 'C', true);
        $this->Cell(30, 10, 'End Time', 1, 0, 'C', true);
        $this->Cell(45, 10, 'Status', 1, 1, 'C', true);
        
        // Table data
        $this->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        $this->SetFont('Arial', '', 10);
        $alternate = false;
        
        foreach ($activities as $activity) {
            // Alternate row colors
            if ($alternate) {
                $this->SetFillColor(240, 240, 240);
            } else {
                $this->SetFillColor(255, 255, 255);
            }
            
            $this->Cell(50, 10, $activity->user->name, 1, 0, 'L', true);
            $this->Cell(35, 10, $activity->start_time->format('Y-m-d'), 1, 0, 'C', true);
            $this->Cell(30, 10, $activity->start_time->format('H:i'), 1, 0, 'C', true);
            $this->Cell(30, 10, $activity->end_time ? $activity->end_time->format('H:i') : 'N/A', 1, 0, 'C', true);
            
            // Color-code status
            $statusText = ucfirst($activity->status);
            if ($activity->status == 'active') {
                $this->SetTextColor(39, 174, 96); // Green
            } else if ($activity->status == 'completed') {
                $this->SetTextColor(41, 128, 185); // Blue
            } else {
                $this->SetTextColor(231, 76, 60); // Red
            }
            
            $this->Cell(45, 10, $statusText, 1, 1, 'C', true);
            $this->SetTextColor($textColor[0], $textColor[1], $textColor[2]); // Reset text color
            
            $alternate = !$alternate;
        }
    }
}