<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the role seeder first to create roles
        $this->call(RoleSeeder::class);
        
        // Then call the user seeder to create users with roles
        $this->call(UserSeeder::class);
    }
}