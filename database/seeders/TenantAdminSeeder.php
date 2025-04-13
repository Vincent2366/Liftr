<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Tenant;

class TenantAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tenantId = tenant('id');
        Log::info('RUNNING TENANT ADMIN SEEDER', ['tenant_id' => $tenantId]);
        
        try {
            // Get the current tenant
            $tenant = Tenant::find($tenantId);
            
            if (!$tenant) {
                throw new \Exception("Tenant not found: $tenantId");
            }
            
            // Get email from tenant metadata
            $email = $tenant->email ?? null;
            
            // Get password from tenant metadata or generate a new one
            $password = $tenant->password ?? Str::random(10);
            $hashedPassword = Hash::make($password);
            
            Log::info('Tenant data retrieved', [
                'tenant_id' => $tenantId,
                'has_email' => !empty($email),
                'has_password' => !empty($password)
            ]);
            
            if (empty($email)) {
                // Try to get email from subdomain_requests as fallback
                try {
                    $subdomainRequest = DB::connection('central')
                        ->table('subdomain_requests')
                        ->where('subdomain', $tenantId)
                        ->where('status', 'approved')
                        ->first();
                    
                    if ($subdomainRequest && !empty($subdomainRequest->email)) {
                        $email = $subdomainRequest->email;
                        Log::info('Using email from subdomain request', ['email' => $email]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error querying central database', [
                        'message' => $e->getMessage()
                    ]);
                }
            }
            
            if (empty($email)) {
                throw new \Exception("Cannot create admin user: No email found for tenant $tenantId");
            }
            
            // Create admin user with the email
            $user = User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => $hashedPassword,
                'role' => 'Admin',
                'email_verified_at' => now(),
            ]);
            
            Log::info('Admin user created successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            Log::info('TENANT ADMIN SEEDER COMPLETE', ['tenant_id' => $tenantId]);
        } catch (\Exception $e) {
            Log::error('FAILED TO CREATE ADMIN USER', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw the exception to halt the tenant creation process
            throw $e;
        }
    }
}





