<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TenantAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => '2001105940@student.buksu.edu.ph',
            'password' => Hash::make('123456'),
            'role' => 'Admin',
            'email_verified_at' => now(),
        ]);
    }
}
