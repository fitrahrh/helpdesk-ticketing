<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id'=> Str::uuid(),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => 1,
            'email_verified_at' => now(),
        ]);

        User::create([
            'id'=> Str::uuid(),
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => 2,
            'email_verified_at' => now(),
        ]);

        User::create([
            'id'=> Str::uuid(),
            'first_name' => 'Teknisi',
            'last_name' => 'User',
            'email' => 'teknisi@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);

        User::create([
            'id'=> Str::uuid(),
            'first_name' => 'User',
            'last_name' => 'Biasa',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123'),
            'role_id' => 4,
            'email_verified_at' => now(),
        ]);

    }
}