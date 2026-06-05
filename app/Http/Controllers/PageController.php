<?php

namespace App\Http\Controllers;

use App\Models\RoomCategory;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Build settings array from cached values.
     */
    private function getSettings(): array
    {
        $all = Setting::getAllCached();
        return [
            'kost_name' => $all['kost_name'] ?? 'Griya Lelana',
            'kost_tagline' => $all['kost_tagline'] ?? 'Hunian Nyaman, Harga Terjangkau',
            'kost_description' => $all['kost_description'] ?? '',
            'kost_address' => $all['kost_address'] ?? '',
            'whatsapp_number' => $all['whatsapp_number'] ?? '',
            'email' => $all['email'] ?? '',
            'google_maps_embed' => $all['google_maps_embed'] ?? '',
        ];
    }

    public function landing()
    {
        $categories = RoomCategory::with(['roomTypes' => function ($q) {
            $q->where('is_active', true)
              ->with('primaryPhoto')
              ->withCount(['rooms' => function ($q2) {
                  $q2->where('status', 'available');
              }]);
        }])->orderBy('sort_order')->get();

        $settings = $this->getSettings();

        return view('landing', compact('categories', 'settings'));
    }

    public function categoryDetail(string $slug)
    {
        $category = RoomCategory::where('slug', $slug)->firstOrFail();

        $types = RoomType::where('category_id', $category->id)
            ->where('is_active', true)
            ->with('primaryPhoto')
            ->withCount(['rooms' => fn($q) => $q->where('status', 'available')])
            ->get();

        // If only 1 type, redirect to Level 3
        if ($types->count() === 1) {
            return redirect()->route('rooms.type', $types->first()->slug);
        }

        $whatsapp = Setting::getAllCached()['whatsapp_number'] ?? '';
        return view('rooms.category', compact('category', 'types', 'whatsapp'));
    }

    public function typeDetail(string $slug)
    {
        $type = RoomType::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'photos' => fn($q) => $q->orderBy('sort_order')])
            ->firstOrFail();

        $rooms = Room::where('room_type_id', $type->id)
            ->orderBy('room_number')
            ->get();

        $whatsapp = Setting::getAllCached()['whatsapp_number'] ?? '';
        return view('rooms.type', compact('type', 'rooms', 'whatsapp'));
    }
}
