<?php


namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic roles
        $roles = [
            [
                'name' => 'Superadmin',
                'hak_akses' => ['all'], // Pass the array directly, let Laravel handle the encoding
            ],
            [
                'name' => 'Admin',
                'hak_akses' => ['tiket', 'disposisi tiket', 'dashboard', 'kelola penanggungjawab', 'laporan'],
            ],
            [
                'name' => 'Teknisi',
                'hak_akses' => ['persetujuan tiket'],
            ],
            [
                'name' => 'User',
                'hak_akses' => ['pengajuan tiket'],
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}