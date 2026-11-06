<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Simpan laporan pelanggaran dari pengunjung/penghuni.
     */
    public function store(Request $request, Kost $kost): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        Report::create([
            'user_id' => auth()->id(),
            'kost_id' => $kost->id,
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Terima kasih atas laporan Anda. Tim moderasi Seven Kost akan meninjau iklan ini dalam 1-3 hari kerja.');
    }
}
