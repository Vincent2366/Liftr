<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TenantAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the tenant ID and metadata
        $tenant = tenant();
        $tenantId = $tenant->id;
        Log::info('Running TenantAdminSeeder for tenant: ' . $tenantId);
        
        // Get the password from tenant metadata, or use default if not found
        $password = $tenant->password ?? '123456';
        
        // Try to get the email from the central database
        try {
            // Connect to central database to get the subdomain request
            $subdomain = \Illuminate\Support\Facades\DB::connection('central')
                ->table('subdomain_requests')
                ->where('subdomain', $tenantId)
                ->where('status', 'approved')
                ->first();
            
            $email = $subdomain ? $subdomain->email : '2001105940@student.buksu.edu.ph';
            
            Log::info('Creating tenant admin user', [
                'email' => $email,
                'tenant' => $tenantId,
                'using_generated_password' => isset($tenant->password)
            ]);
            
            // Create the admin user with the password from tenant metadata
            User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => $password, // This will be hashed by the User model's setPasswordAttribute method
                'role' => 'Admin',
                'email_verified_at' => now(),
            ]);
            
            Log::info('Tenant admin user created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating tenant admin: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            // Fallback to default admin with default password
            User::create([
                'name' => 'Admin',
                'email' => '2001105940@student.buksu.edu.ph',
                'password' => $password,
                'role' => 'Admin',
                'email_verified_at' => now(),
            ]);
        }
    }
}


