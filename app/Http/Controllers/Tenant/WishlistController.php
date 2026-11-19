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
    public function toggle(Request $request, string|Kost $kost): JsonResponse|RedirectResponse
    {
        $kostModel = $kost instanceof Kost ? $kost : (is_numeric($kost) ? Kost::findOrFail($kost) : Kost::where('slug', $kost)->firstOrFail());

        $user = auth()->user();
        $existing = Wishlist::where('user_id', $user->id)
            ->where('kost_id', $kostModel->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavorited = false;
            $message = 'Kos berhasil dihapus dari daftar favorit.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'kost_id' => $kostModel->id,
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
