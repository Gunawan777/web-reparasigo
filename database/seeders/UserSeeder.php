<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin User
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // password
            'phone_number' => '080000000000',
            'address' => 'Jl. Admin No. 1, Pusat',
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Default Customer
        DB::table('users')->insert([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'password' => Hash::make('password'), // password
            'phone_number' => '081234567890',
            'address' => 'Jl. Pelanggan No. 1, Jakarta',
            'role' => 'pelanggan',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Default Teknisi
        DB::table('users')->insert([
            'name' => 'Teknisi Uji',
            'email' => 'teknisi@example.com',
            'password' => Hash::make('password'), // password
            'phone_number' => '089876543210',
            'address' => 'Jl. Teknisi No. 1, Bandung',
            'role' => 'teknisi',
            'status' => 'tersedia', // Default status for technician
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
