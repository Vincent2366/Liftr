<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'mail:send-test';
    protected $description = 'Send a test email to verify SMTP configuration';

    public function handle()
    {
        $recipient = '2001105940@student.buksu.edu.ph';
        
        $this->info("Sending test email to {$recipient}...");
        
        try {
            Mail::raw('This is a test email from Laravel Artisan command', function ($message) use ($recipient) {
                $message->to($recipient)
                        ->subject('Laravel Gmail SMTP Test');
            });
            
            $this->info('Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to send test email: ' . $e->getMessage());
        }
    }
}