<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TenantAssetsLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:assets-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create symbolic links for tenant assets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create the tenancy/assets directory if it doesn't exist
        $tenancyAssetsDir = public_path('tenancy/assets');
        if (!File::isDirectory($tenancyAssetsDir)) {
            File::makeDirectory($tenancyAssetsDir, 0755, true);
        }

        // Create a symbolic link for the img directory
        $imgTarget = public_path('img');
        $imgLink = public_path('tenancy/assets/img');
        
        if (File::exists($imgLink)) {
            File::delete($imgLink);
        }
        
        if (File::isDirectory($imgTarget)) {
            $this->info('Creating symbolic link for img directory...');
            File::link($imgTarget, $imgLink);
        }

        // Create a symbolic link for the storage directory
        $storageTarget = storage_path('app/public');
        $storageLink = public_path('tenancy/assets/storage');
        
        if (File::exists($storageLink)) {
            File::delete($storageLink);
        }
        
        if (File::isDirectory($storageTarget)) {
            $this->info('Creating symbolic link for storage directory...');
            File::link($storageTarget, $storageLink);
        }

        $this->info('Tenant assets links created successfully!');
    }
}