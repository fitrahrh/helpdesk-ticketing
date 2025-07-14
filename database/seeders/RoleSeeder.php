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
                'hak_akses' => ['all', 'data_master'], // Pass the array directly, let Laravel handle the encoding
            ],
            [
                'name' => 'Admin',
                'hak_akses' => [
                    'dashboard',              // Akses ke halaman Dashboard
                    'kelola_penanggungjawab', // Akses ke halaman Kelola Penanggung Jawab
                    'kelola_menu_tiket',      // Akses ke halaman Kelola Menu Tiket
                    'disposisi_tiket',        // Akses ke halaman Disposisi Tiket
                    'riwayat_tiket',          // Akses ke halaman Riwayat Tiket
                    'laporan',                // Akses ke halaman Laporan
                ],
            ],
            [
                'name' => 'Teknisi',
                'hak_akses' => ['akses_teknisi'],
            ],
            [
                'name' => 'User',
                'hak_akses' => ['akses_user'],
            ],
        ];

        // Hapus data lama sebelum membuat yang baru jika seeder dijalankan ulang
        // Role::truncate(); // Hati-hati menggunakan ini di produksi

        foreach ($roles as $role) {
            // Gunakan updateOrCreate agar tidak membuat duplikat jika role sudah ada
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['hak_akses' => $role['hak_akses']]
            );
        }
    }
}