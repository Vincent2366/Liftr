<?php

namespace Database\Seeders;

use App\Models\CentralUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CentralUserSeeder extends Seeder
{
    public function run(): void
    {
        CentralUser::create([
            'name' => 'Super Admin',
            'email' => 'admin@liftr.com',
            'password' => Hash::make('password'), // Change this to a secure password
            'is_super_admin' => true,
        ]);
    }
}
