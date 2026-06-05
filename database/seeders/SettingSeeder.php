<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'kost_name', 'value' => 'Griya Lelana', 'type' => 'string', 'description' => 'Nama kost'],
            ['key' => 'kost_description', 'value' => 'Kost nyaman dan strategis untuk mahasiswa & karyawan', 'type' => 'string', 'description' => 'Deskripsi kost'],
            ['key' => 'kost_address', 'value' => 'Jl. Contoh No. 123, Kota', 'type' => 'string', 'description' => 'Alamat kost'],
            ['key' => 'kost_tagline', 'value' => 'Hunian Nyaman, Harga Terjangkau', 'type' => 'string', 'description' => 'Tagline di hero section'],
            ['key' => 'whatsapp_number', 'value' => '6281234567890', 'type' => 'string', 'description' => 'Nomor WhatsApp owner untuk janji temu'],
            ['key' => 'email', 'value' => 'info@griyalelana.com', 'type' => 'string', 'description' => 'Email kontak'],
            ['key' => 'grace_period_days', 'value' => '7', 'type' => 'integer', 'description' => 'Toleransi keterlambatan bayar (hari)'],
            ['key' => 'booking_expire_hours', 'value' => '12', 'type' => 'integer', 'description' => 'Batas waktu bayar DP setelah booking dikonfirmasi (jam)'],
            ['key' => 'dp_percentage', 'value' => '30', 'type' => 'integer', 'description' => 'Persentase DP untuk Opsi A'],
            ['key' => 'google_maps_embed', 'value' => '', 'type' => 'string', 'description' => 'URL embed Google Maps'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
