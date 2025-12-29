<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder ini digunakan untuk membuat akun default sistem gudang:
     * 1. Admin       → akses penuh (role: admin)
     * 2. Petugas     → akses operasional (role: staff)
     *
     * Menggunakan firstOrCreate agar aman dijalankan berulang kali
     * tanpa membuat data duplikat.
     *
     * Email & Password default:
     * - admin@gudang.com   | 123456
     * - petugas@gudang.com | 123456
     */
    public function run(): void
    {
        // =======================
        // Akun Administrator
        // =======================
        User::firstOrCreate(
            ['email' => 'admin@gudang.com'],
            [
                'name'     => 'Administrator',
                'password' => bcrypt('123456'),
                'role'     => 'admin',
            ]
        );

        // =======================
        // Akun Petugas Gudang
        // =======================
        User::firstOrCreate(
            ['email' => 'petugas@gudang.com'],
            [
                'name'     => 'Petugas Gudang',
                'password' => bcrypt('123456'),
                'role'     => 'staff',
            ]
        );
    }
}
