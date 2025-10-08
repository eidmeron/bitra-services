<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Städning',
                'description' => 'Hemstädning, kontorsstädning och flyttstädning',
                'icon' => '🧹',
                'sort_order' => 1,
            ],
            [
                'name' => 'Hantverkare',
                'description' => 'VVS, el, målning och andra hantverkstjänster',
                'icon' => '🔧',
                'sort_order' => 2,
            ],
            [
                'name' => 'Trädgård',
                'description' => 'Trädgårdsskötsel, gräsklippning och beskärning',
                'icon' => '🌱',
                'sort_order' => 3,
            ],
            [
                'name' => 'Flytt',
                'description' => 'Flytthjälp och packning',
                'icon' => '📦',
                'sort_order' => 4,
            ],
            [
                'name' => 'Reparationer',
                'description' => 'Reparation av vitvaror, elektronik och möbler',
                'icon' => '🛠️',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'sort_order' => $category['sort_order'],
                'status' => 'active',
            ]);
        }
    }
}

