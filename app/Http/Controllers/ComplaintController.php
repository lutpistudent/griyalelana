<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $contract = $user->activeContract();

        if (!$contract) {
            return redirect()->route('dashboard')
                ->with('error', 'Fitur keluhan hanya tersedia untuk penyewa dengan kontrak aktif.');
        }

        $complaints = $user->complaints()
            ->with('room')
            ->latest()
            ->paginate(10);

        return view('complaints.index', compact('user', 'contract', 'complaints'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $contract = $user->activeContract();

        if (!$contract) {
            return redirect()->route('dashboard')
                ->with('error', 'Fitur keluhan hanya tersedia untuk penyewa dengan kontrak aktif.');
        }

        return view('complaints.create', compact('user', 'contract'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $contract = $user->activeContract();

        if (!$contract) {
            return redirect()->route('dashboard')
                ->with('error', 'Fitur keluhan hanya tersedia untuk penyewa dengan kontrak aktif.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:2000',
        ]);

        $user->complaints()->create([
            'room_id' => $contract->room_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'open',
        ]);

        return redirect()->route('complaints.index')
            ->with('success', 'Keluhan berhasil dikirim. Owner akan segera merespons.');
    }
}
