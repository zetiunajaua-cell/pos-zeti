<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Makanan',
            'slug' => Str::slug('Makanan'),
        ]);

        Category::create([
            'name' => 'Minuman',
            'slug' => Str::slug('Minuman'),
        ]);

        Category::create([
            'name' => 'Snack',
            'slug' => Str::slug('Snack'),
        ]);
    }
}