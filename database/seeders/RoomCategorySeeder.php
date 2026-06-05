<?php

namespace Database\Seeders;

use App\Models\RoomCategory;
use Illuminate\Database\Seeder;

class RoomCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Lantai 1', 'slug' => 'lantai-1', 'description' => 'Kamar di lantai 1, akses mudah tanpa tangga', 'sort_order' => 1],
            ['name' => 'Lantai 2', 'slug' => 'lantai-2', 'description' => 'Kamar di lantai 2, view lebih baik', 'sort_order' => 2],
            ['name' => 'Lantai 3', 'slug' => 'lantai-3', 'description' => 'Kamar di lantai 3, suasana tenang', 'sort_order' => 3],
            ['name' => 'Kamar Mandi Luar', 'slug' => 'kamar-mandi-luar', 'description' => 'Kamar budget-friendly dengan kamar mandi bersama', 'sort_order' => 4],
            ['name' => 'Premium', 'slug' => 'premium', 'description' => 'Kamar premium dengan fasilitas terbaik', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            RoomCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
