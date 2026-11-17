<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Tampilkan daftar kos yang difavoritkan oleh penghuni.
     */
    public function index(): View
    {
        $kosts = auth()->user()
            ->favoriteKosts()
            ->with(['province', 'regency', 'primaryImage', 'facilities'])
            ->latest('wishlists.created_at')
            ->paginate(12);

        return view('tenant.wishlists', compact('kosts'));
    }

    /**
     * Toggle simpan / hapus kos dari wishlist.
     */
    public function toggle(Request $request, Kost $kost): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        $existing = Wishlist::where('user_id', $user->id)
            ->where('kost_id', $kost->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavorited = false;
            $message = 'Kos berhasil dihapus dari daftar favorit.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'kost_id' => $kost->id,
            ]);
            $isFavorited = true;
            $message = 'Kos berhasil ditambahkan ke daftar favorit!';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'message' => $message,
                'total_wishlists' => $user->wishlists()->count(),
            ]);
        }

        return back()->with('success', $message);
    }
}
