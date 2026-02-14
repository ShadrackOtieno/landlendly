<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rentify.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '254712345678',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Landlord users with trial
        $landlords = [
            [
                'name' => 'John Doe',
                'email' => 'landlord1@example.com',
                'phone' => '254723456789',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'landlord2@example.com',
                'phone' => '254734567890',
            ],
            [
                'name' => 'David Wilson',
                'email' => 'landlord3@example.com',
                'phone' => '254745678901',
            ],
        ];

        foreach ($landlords as $landlord) {
            User::create([
                'name' => $landlord['name'],
                'email' => $landlord['email'],
                'password' => Hash::make('password'),
                'role' => 'landlord',
                'phone' => $landlord['phone'],
                'trial_ends_at' => Carbon::now()->addDays(14),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Tenant users
        $tenants = [
            [
                'name' => 'Alice Johnson',
                'email' => 'tenant1@example.com',
                'phone' => '254756789012',
            ],
            [
                'name' => 'Bob Williams',
                'email' => 'tenant2@example.com',
                'phone' => '254767890123',
            ],
        ];

        foreach ($tenants as $tenant) {
            User::create([
                'name' => $tenant['name'],
                'email' => $tenant['email'],
                'password' => Hash::make('password'),
                'role' => 'tenant',
                'phone' => $tenant['phone'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}
