<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->exists()) {
            return;
        }

        $attributes = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@domain.rw',
                'password' => bcrypt('password'),
                'is_super_admin' => true,
                'phone_number' => '0780000000',
                'email_verified_at' => now(),
                'password_changed_at' => now()
            ],
            [
                'name' => 'Customer Care',
                'email' => 'cc@domain.rw',
                'password' => bcrypt('password'),
                'is_super_admin' => true,
                'phone_number' => '0780000000',
                'email_verified_at' => now(),
                'password_changed_at' => now()
            ],
            [
                'name' => 'Store Keeper',
                'email' => 'sk@domain.rw',
                'password' => bcrypt('password'),
                'is_super_admin' => true,
                'phone_number' => '0780000000',
                'email_verified_at' => now(),
                'password_changed_at' => now()
            ],
            [
                'name' => 'Delivery Person',
                'email' => 'delivery@domain.rw',
                'password' => bcrypt('password'),
                'is_super_admin' => true,
                'phone_number' => '0780000000',
                'email_verified_at' => now(),
                'password_changed_at' => now()
            ],
            [
                'name' => 'Finance User',
                'email' => 'finance@domain.rw',
                'password' => bcrypt('password'),
                'is_super_admin' => true,
                'phone_number' => '0780000000',
                'email_verified_at' => now(),
                'password_changed_at' => now()
            ],
            [
                'name' => 'Sales Manager',
                'email' => 'sales@domain.rw',
                'password' => bcrypt('password'),
                'is_super_admin' => true,
                'phone_number' => '0780000000',
                'email_verified_at' => now(),
                'password_changed_at' => now()
            ]
        ];

        \DB::table('users')->insert($attributes);

    }
}
