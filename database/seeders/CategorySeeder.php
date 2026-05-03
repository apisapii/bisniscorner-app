<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'icon' => '🍜', 'sort_order' => 1],
            ['name' => 'Minuman', 'slug' => 'minuman', 'icon' => '🥤', 'sort_order' => 2],
            ['name' => 'Snack', 'slug' => 'snack', 'icon' => '🍿', 'sort_order' => 3],
            ['name' => 'Pakaian', 'slug' => 'pakaian', 'icon' => '👕', 'sort_order' => 4],
            ['name' => 'Lainnya', 'slug' => 'lainnya', 'icon' => '📦', 'sort_order' => 99],
        ];

        foreach ($rows as $row) {
            Category::query()->firstOrCreate(
                ['slug' => $row['slug']],
                $row
            );
        }
    }
}
