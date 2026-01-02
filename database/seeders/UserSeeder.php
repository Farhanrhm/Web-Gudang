<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Akun ADMIN
        // 1. Akun ADMIN
        User::firstOrCreate(
            ['email' => 'admin@gudang.com'],
            [
                'name' => 'Owner Gudang',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Akun KARYAWAN
        User::firstOrCreate(
            ['email' => 'staff@gudang.com'],
            [
                'name' => 'Staff Gudang',
                'password' => Hash::make('staff123'),
                'role' => 'karyawan',
            ]
        );
    }
}