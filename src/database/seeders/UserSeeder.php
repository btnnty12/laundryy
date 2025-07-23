<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Owner Account
        $owner = User::firstOrCreate(
            ['email' => 'owner@laundry.com'],
            [
                'name' => 'Owner Laundry',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'address' => 'Jl. Laundry Owner No. 1',
                'role' => 'owner',
                'is_active' => true,
            ]
        );
        $owner->assignRole('owner');

        // Admin Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Admin Laundry',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'address' => 'Jl. Laundry Admin No. 2',
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // Customer Accounts
        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@customer.com',
                'phone' => '081234567892',
                'address' => 'Jl. Customer No. 1, Jakarta',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@customer.com',
                'phone' => '081234567893',
                'address' => 'Jl. Customer No. 2, Bandung',
            ],
            [
                'name' => 'Ahmad Rahman',
                'email' => 'ahmad@customer.com',
                'phone' => '081234567894',
                'address' => 'Jl. Customer No. 3, Surabaya',
            ],
        ];

        foreach ($customers as $customerData) {
            $customer = User::firstOrCreate(
                ['email' => $customerData['email']],
                [
                    'name' => $customerData['name'],
                    'password' => Hash::make('password'),
                    'phone' => $customerData['phone'],
                    'address' => $customerData['address'],
                    'role' => 'customer',
                    'is_active' => true,
                ]
            );
            $customer->assignRole('customer');
        }
    }
}
