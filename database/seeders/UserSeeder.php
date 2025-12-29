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
        User::create([
            'name' => 'Owner Gudang',
            'email' => 'admin@gudang.com', // <--- Cek Email
            'password' => Hash::make('admin123'), // <--- Passwordnya admin123
            'role' => 'admin', 
        ]);

        // 2. Akun KARYAWAN
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@gudang.com', // <--- Cek Email
            'password' => Hash::make('staff123'), // <--- Passwordnya staff123
            'role' => 'karyawan',
        ]);
    }
}