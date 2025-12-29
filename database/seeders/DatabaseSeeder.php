<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan firstOrCreate agar tidak error jika dijalankan ulang
        User::firstOrCreate(
            ['email' => 'admin@gudang.com'], // Cek berdasarkan email
            [
                'name' => 'Administrator',
                'password' => bcrypt('123456'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'petugas@gudang.com'],
            [
                'name' => 'Petugas Gudang',
                'password' => bcrypt('123456'),
                'role' => 'staff',
            ]
        );
    }
}