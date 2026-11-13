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

        // Catat klik iklan & akumulasi biaya CPC jika kos bertatus iklan / unggulan
        if ($kost->is_featured || request()->boolean('from_ad')) {
            $isOwner = auth()->check() && auth()->id() === $kost->user_id;
            if (! $isOwner) {
                $cpcFee = (float) setting('ad_cpc_fee', 0);
                $kost->increment('ad_clicks_count');

                $activePromotion = $kost->promotions()
                    ->where('status', 'active')
                    ->latest()
                    ->first();

                if ($activePromotion) {
                    $activePromotion->increment('click_count');
                    if ($cpcFee > 0) {
                        $activePromotion->increment('cpc_spent', $cpcFee);
                    }
                }
            }
        }

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
