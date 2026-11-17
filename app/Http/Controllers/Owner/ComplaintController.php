<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    /**
     * Tampilkan daftar keluhan & laporan untuk kos milik pemilik.
     */
    public function index(Request $request): View
    {
        $ownerKostIds = auth()->user()->kosts()->pluck('id');
        $tab = $request->query('tab', 'all');

        $query = Report::with(['kost', 'reporter'])
            ->whereIn('kost_id', $ownerKostIds);

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'reviewed') {
            $query->where('status', 'reviewed');
        } elseif ($tab === 'resolved') {
            $query->where('status', 'resolved');
        }

        $complaints = $query->latest()->get();

        $counts = [
            'all' => Report::whereIn('kost_id', $ownerKostIds)->count(),
            'pending' => Report::whereIn('kost_id', $ownerKostIds)->where('status', 'pending')->count(),
            'reviewed' => Report::whereIn('kost_id', $ownerKostIds)->where('status', 'reviewed')->count(),
            'resolved' => Report::whereIn('kost_id', $ownerKostIds)->where('status', 'resolved')->count(),
        ];

        return view('owner.complaints.index', compact('complaints', 'tab', 'counts'));
    }

    /**
     * Perbarui status penanganan keluhan dan tanggapan pemilik kos.
     */
    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        if ($report->kost->user_id !== auth()->id() && ! auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update($validated);

        return back()->with('success', 'Status dan tanggapan keluhan berhasil diperbarui.');
    }
}
