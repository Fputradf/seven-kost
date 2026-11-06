<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\View\View;

class KostDetailController extends Controller
{
    /**
     * Tampilkan detail kos publik berdasarkan slug.
     */
    public function show(string $slug): View
    {
        $kost = Kost::with([
            'province',
            'regency',
            'facilities',
            'rooms',
            'images',
            'owner',
        ])->where('slug', $slug)->firstOrFail();

        // Rekomendasi kos lain di wilayah yang sama
        $similarKosts = Kost::with(['province', 'regency', 'facilities', 'rooms', 'primaryImage'])
            ->where('id', '!=', $kost->id)
            ->where(function ($q) use ($kost) {
                $q->where('regency_id', $kost->regency_id)
                  ->orWhere('province_id', $kost->province_id);
            })
            ->where('verification_status', 'verified')
            ->take(3)
            ->get();

        return view('kost-detail', compact('kost', 'similarKosts'));
    }
}
