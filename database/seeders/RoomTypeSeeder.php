<?php

namespace Database\Seeders;

use App\Models\RoomCategory;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $categories = RoomCategory::pluck('id', 'slug');

        $types = [
            [
                'category_id' => $categories['lantai-1'],
                'name' => 'Lantai 1 Non-AC',
                'slug' => 'lantai-1-non-ac',
                'description' => 'Kamar lantai 1 dengan ventilasi alami, kamar mandi dalam',
                'price_per_year' => 10000000,
                'has_ac' => false,
                'bathroom_type' => 'inside',
                'bed_size' => 'Single',
                'room_size' => 12.00,
                'facilities' => ['Ventilasi', 'KM Dalam', 'Kasur', 'Lemari', 'Meja Belajar'],
            ],
            [
                'category_id' => $categories['lantai-1'],
                'name' => 'Lantai 1 AC',
                'slug' => 'lantai-1-ac',
                'description' => 'Kamar lantai 1 dengan AC dan kamar mandi dalam',
                'price_per_year' => 13000000,
                'has_ac' => true,
                'bathroom_type' => 'inside',
                'bed_size' => 'Single',
                'room_size' => 12.00,
                'facilities' => ['AC', 'KM Dalam', 'Kasur', 'Lemari', 'Meja Belajar'],
            ],
            [
                'category_id' => $categories['lantai-2'],
                'name' => 'Lantai 2 Non-AC',
                'slug' => 'lantai-2-non-ac',
                'description' => 'Kamar lantai 2 dengan ventilasi alami, kamar mandi dalam',
                'price_per_year' => 11000000,
                'has_ac' => false,
                'bathroom_type' => 'inside',
                'bed_size' => 'Single',
                'room_size' => 12.00,
                'facilities' => ['Ventilasi', 'KM Dalam', 'Kasur', 'Lemari', 'Meja Belajar'],
            ],
            [
                'category_id' => $categories['lantai-2'],
                'name' => 'Lantai 2 AC',
                'slug' => 'lantai-2-ac',
                'description' => 'Kamar lantai 2 dengan AC dan kamar mandi dalam',
                'price_per_year' => 14000000,
                'has_ac' => true,
                'bathroom_type' => 'inside',
                'bed_size' => 'Single',
                'room_size' => 12.00,
                'facilities' => ['AC', 'KM Dalam', 'Kasur', 'Lemari', 'Meja Belajar'],
            ],
            [
                'category_id' => $categories['lantai-3'],
                'name' => 'Lantai 3 Non-AC',
                'slug' => 'lantai-3-non-ac',
                'description' => 'Kamar lantai 3 dengan ventilasi alami, suasana tenang',
                'price_per_year' => 12000000,
                'has_ac' => false,
                'bathroom_type' => 'inside',
                'bed_size' => 'Single',
                'room_size' => 14.00,
                'facilities' => ['Ventilasi', 'KM Dalam', 'Kasur', 'Lemari', 'Meja Belajar'],
            ],
            [
                'category_id' => $categories['kamar-mandi-luar'],
                'name' => 'Kamar Mandi Luar',
                'slug' => 'kamar-mandi-luar',
                'description' => 'Kamar budget-friendly dengan kamar mandi bersama',
                'price_per_year' => 9000000,
                'has_ac' => false,
                'bathroom_type' => 'outside',
                'bed_size' => 'Single',
                'room_size' => 10.00,
                'facilities' => ['Ventilasi', 'KM Luar', 'Kasur', 'Lemari'],
            ],
            [
                'category_id' => $categories['premium'],
                'name' => 'Premium Non-AC',
                'slug' => 'premium-non-ac',
                'description' => 'Kamar premium dengan fasilitas lengkap',
                'price_per_year' => 16000000,
                'has_ac' => false,
                'bathroom_type' => 'inside',
                'bed_size' => 'Queen',
                'room_size' => 18.00,
                'facilities' => ['Ventilasi Premium', 'KM Dalam', 'Kasur Queen', 'Lemari Besar', 'Meja Belajar', 'Rak Buku'],
            ],
            [
                'category_id' => $categories['premium'],
                'name' => 'Premium AC',
                'slug' => 'premium-ac',
                'description' => 'Kamar premium terbaik dengan AC dan fasilitas lengkap',
                'price_per_year' => 20000000,
                'has_ac' => true,
                'bathroom_type' => 'inside',
                'bed_size' => 'Queen',
                'room_size' => 18.00,
                'facilities' => ['AC Premium', 'KM Dalam', 'Kasur Queen', 'Lemari Besar', 'Meja Belajar', 'Rak Buku', 'TV'],
            ],
        ];

        foreach ($types as $type) {
            RoomType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
