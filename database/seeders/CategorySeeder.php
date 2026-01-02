<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Elektronik',
            'Sembako',
            'Pakaian',
            'Peralatan Rumah Tangga',
            'Funiture'
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }
    }
}
