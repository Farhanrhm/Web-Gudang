<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada category dulu, dipanggil di DatabaseSeeder
        Product::factory()->count(20)->create();
    }
}
