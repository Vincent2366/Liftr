<?php

namespace Database\Seeders;

use App\Domains\SuperAdmin\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'SuperAdmins',
            'email' => '2001105940@student.buksu.edu.ph',
            'password' => Hash::make('admin123'),
            'is_super_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
