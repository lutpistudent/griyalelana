<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $types = RoomType::pluck('id', 'slug');

        $rooms = [
            // Lantai 1 Non-AC (5 kamar)
            ['room_type_id' => $types['lantai-1-non-ac'], 'room_number' => 'A-01', 'floor' => 1, 'position' => 'Depan'],
            ['room_type_id' => $types['lantai-1-non-ac'], 'room_number' => 'A-02', 'floor' => 1, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-1-non-ac'], 'room_number' => 'A-03', 'floor' => 1, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-1-non-ac'], 'room_number' => 'A-04', 'floor' => 1, 'position' => 'Belakang'],
            ['room_type_id' => $types['lantai-1-non-ac'], 'room_number' => 'A-05', 'floor' => 1, 'position' => 'Belakang'],

            // Lantai 1 AC (3 kamar)
            ['room_type_id' => $types['lantai-1-ac'], 'room_number' => 'A-06', 'floor' => 1, 'position' => 'Depan', 'notes' => 'View taman'],
            ['room_type_id' => $types['lantai-1-ac'], 'room_number' => 'A-07', 'floor' => 1, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-1-ac'], 'room_number' => 'A-08', 'floor' => 1, 'position' => 'Belakang'],

            // Lantai 2 Non-AC (4 kamar)
            ['room_type_id' => $types['lantai-2-non-ac'], 'room_number' => 'B-01', 'floor' => 2, 'position' => 'Depan'],
            ['room_type_id' => $types['lantai-2-non-ac'], 'room_number' => 'B-02', 'floor' => 2, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-2-non-ac'], 'room_number' => 'B-03', 'floor' => 2, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-2-non-ac'], 'room_number' => 'B-04', 'floor' => 2, 'position' => 'Belakang'],

            // Lantai 2 AC (4 kamar)
            ['room_type_id' => $types['lantai-2-ac'], 'room_number' => 'B-05', 'floor' => 2, 'position' => 'Depan', 'notes' => 'Dekat tangga'],
            ['room_type_id' => $types['lantai-2-ac'], 'room_number' => 'B-06', 'floor' => 2, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-2-ac'], 'room_number' => 'B-07', 'floor' => 2, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-2-ac'], 'room_number' => 'B-08', 'floor' => 2, 'position' => 'Belakang'],

            // Lantai 3 Non-AC (5 kamar)
            ['room_type_id' => $types['lantai-3-non-ac'], 'room_number' => 'C-01', 'floor' => 3, 'position' => 'Depan', 'notes' => 'View kota'],
            ['room_type_id' => $types['lantai-3-non-ac'], 'room_number' => 'C-02', 'floor' => 3, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-3-non-ac'], 'room_number' => 'C-03', 'floor' => 3, 'position' => 'Tengah'],
            ['room_type_id' => $types['lantai-3-non-ac'], 'room_number' => 'C-04', 'floor' => 3, 'position' => 'Belakang'],
            ['room_type_id' => $types['lantai-3-non-ac'], 'room_number' => 'C-05', 'floor' => 3, 'position' => 'Belakang'],

            // Kamar Mandi Luar (4 kamar)
            ['room_type_id' => $types['kamar-mandi-luar'], 'room_number' => 'D-01', 'floor' => 1, 'position' => 'Belakang'],
            ['room_type_id' => $types['kamar-mandi-luar'], 'room_number' => 'D-02', 'floor' => 1, 'position' => 'Belakang'],
            ['room_type_id' => $types['kamar-mandi-luar'], 'room_number' => 'D-03', 'floor' => 1, 'position' => 'Belakang'],
            ['room_type_id' => $types['kamar-mandi-luar'], 'room_number' => 'D-04', 'floor' => 1, 'position' => 'Belakang'],

            // Premium Non-AC (3 kamar)
            ['room_type_id' => $types['premium-non-ac'], 'room_number' => 'P-01', 'floor' => 2, 'position' => 'Depan', 'notes' => 'Kamar terluas'],
            ['room_type_id' => $types['premium-non-ac'], 'room_number' => 'P-02', 'floor' => 2, 'position' => 'Depan'],
            ['room_type_id' => $types['premium-non-ac'], 'room_number' => 'P-03', 'floor' => 2, 'position' => 'Tengah'],

            // Premium AC (2 kamar)
            ['room_type_id' => $types['premium-ac'], 'room_number' => 'P-04', 'floor' => 2, 'position' => 'Depan', 'notes' => 'Suite terbaik, view taman'],
            ['room_type_id' => $types['premium-ac'], 'room_number' => 'P-05', 'floor' => 2, 'position' => 'Depan'],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(['room_number' => $room['room_number']], array_merge($room, ['status' => 'available']));
        }
    }
}
