<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarketplaceItemRequest;
use App\Http\Requests\UpdateMarketplaceItemRequest;
use App\Models\Banner;
use App\Models\MarketplaceItem;
use App\Models\MarketplaceItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MarketplaceController extends Controller
{
    /**
     * Tampilkan katalog marketplace barang kos untuk publik (guest).
     */
    public function index(Request $request)
    {
        $category = $request->query('kategori');
        $search = $request->query('q');
        $city = $request->query('kota');
        $sort = $request->query('urutkan', 'terbaru');

        $query = MarketplaceItem::with(['user', 'images'])
            ->where('status', 'active');

        if ($category && $category !== 'semua') {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('meetup_location', 'like', "%{$search}%");
            });
        }

        if ($city && $city !== 'semua') {
            $query->where('city', 'like', "%{$city}%");
        }

        // Sorting
        match ($sort) {
            'termurah' => $query->orderBy('price', 'asc'),
            'termahal' => $query->orderBy('price', 'desc'),
            'populer' => $query->orderBy('views_count', 'desc'),
            default => $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
        };

        $items = $query->paginate(16)->withQueryString();

        // Banner Iklan Komersial Aktif untuk Marketplace
        $banners = Banner::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest()
            ->take(4)
            ->get();

        // Kategori terdata
        $categories = [
            'semua' => ['label' => 'Semua Barang', 'icon' => '📦'],
            'kasur' => ['label' => 'Kasur', 'icon' => '🛏️'],
            'kipas' => ['label' => 'Kipas', 'icon' => '🌀'],
            'rak' => ['label' => 'Rak & Lemari', 'icon' => '🗄️'],
            'perabot' => ['label' => 'Perabot Kamar', 'icon' => '🪑'],
            'elektronik' => ['label' => 'Elektronik Kecil', 'icon' => '💻'],
            'buku' => ['label' => 'Buku & Kuliah', 'icon' => '📚'],
        ];

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        if ($isTenant) {
            return view('tenant.marketplace.index', compact('items', 'categories', 'category', 'search', 'city', 'sort', 'banners'));
        }

        return view('marketplace.index', compact('items', 'categories', 'category', 'search', 'city', 'sort', 'banners'));
    }

    /**
     * Tampilkan formulir jual barang (hanya untuk pengguna terotentikasi).
     */
    public function create()
    {
        $categories = [
            'perabot' => 'Perabot Kamar (Meja, Kursi, Cermin)',
            'kasur' => 'Kasur & Bantal Guling',
            'kipas' => 'Kipas Angin',
            'rak' => 'Rak Besi / Lemari Plastik',
            'elektronik' => 'Elektronik Kecil (Rice Cooker, Dispenser, Setrika)',
            'buku' => 'Buku Kuliah & Perlengkapan Belajar',
            'lainnya' => 'Lainnya',
        ];

        $conditions = [
            'bekas_baik' => 'Bekas (Baik - Layak Pakai)',
            'bekas_seperti_baru' => 'Bekas (Seperti Baru / Mulus)',
            'baru' => 'Baru (Belum Pernah Dipakai)',
            'bekas_cukup' => 'Bekas (Cukup - Ada Bekas Pemakaian Wajar)',
        ];

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        if ($isTenant) {
            return view('tenant.marketplace.create', compact('categories', 'conditions'));
        }

        return view('marketplace.create', compact('categories', 'conditions'));
    }

    /**
     * Simpan postingan barang baru.
     */
    public function store(StoreMarketplaceItemRequest $request)
    {
        $validated = $request->validated();

        $item = MarketplaceItem::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'category' => $validated['category'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'meetup_location' => $validated['meetup_location'],
            'city' => $validated['city'] ?? null,
            'description' => $validated['description'],
            'is_featured' => $request->boolean('is_featured'),
            'status' => 'pending',
            'views_count' => 0,
        ]);

        if ($request->hasFile('images')) {
            $order = 1;
            foreach ($request->file('images') as $file) {
                $path = $file->store('marketplace', 'public');

                MarketplaceItemImage::create([
                    'marketplace_item_id' => $item->id,
                    'image_path' => $path,
                    'is_primary' => $order === 1,
                    'order' => $order,
                ]);

                $order++;
            }
        }

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        $successMsg = 'Barang Anda berhasil diajukan dan sedang menunggu persetujuan (approval) oleh Admin sebelum tayang di Marketplace.';

        if ($isTenant) {
            return redirect()->route('tenant.marketplace.my-items')->with('success', $successMsg);
        }

        return redirect()->route('marketplace.index')->with('success', $successMsg);
    }

    /**
     * Tampilkan daftar barang yang diajukan oleh pengguna login.
     */
    public function myItems()
    {
        $items = MarketplaceItem::with(['images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        $pendingCount = MarketplaceItem::where('user_id', auth()->id())->where('status', 'pending')->count();
        $activeCount = MarketplaceItem::where('user_id', auth()->id())->where('status', 'active')->count();
        $soldCount = MarketplaceItem::where('user_id', auth()->id())->where('status', 'sold')->count();

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        if ($isTenant) {
            return view('tenant.marketplace.my-items', compact('items', 'pendingCount', 'activeCount', 'soldCount'));
        }

        return view('marketplace.my-items', compact('items', 'pendingCount', 'activeCount', 'soldCount'));
    }

    /**
     * Tampilkan form untuk mengedit postingan barang.
     */
    public function edit(MarketplaceItem $marketplaceItem)
    {
        $isOwner = $marketplaceItem->user_id === auth()->id();
        $isAdmin = auth()->check() && auth()->user()->hasRole(['superadmin', 'admin']);

        if (! $isOwner && ! $isAdmin) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit barang ini.');
        }

        $categories = [
            'perabot' => 'Perabot Kamar',
            'kasur' => 'Kasur & Bantal',
            'kipas' => 'Kipas Angin & Pendingin',
            'rak' => 'Rak & Lemari Portable',
            'elektronik' => 'Elektronik & Dapur',
            'buku' => 'Buku & Kuliah',
            'lainnya' => 'Lainnya',
        ];

        $conditions = [
            'bekas_baik' => 'Bekas (Baik - Layak Pakai)',
            'bekas_seperti_baru' => 'Bekas (Seperti Baru / Mulus)',
            'baru' => 'Baru (Belum Pernah Dipakai)',
            'bekas_cukup' => 'Bekas (Cukup - Ada Bekas Pemakaian Wajar)',
        ];

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        if ($isTenant) {
            return view('tenant.marketplace.edit', compact('marketplaceItem', 'categories', 'conditions'));
        }

        return view('marketplace.edit', compact('marketplaceItem', 'categories', 'conditions'));
    }

    /**
     * Perbarui postingan barang yang sudah ada.
     */
    public function update(UpdateMarketplaceItemRequest $request, MarketplaceItem $marketplaceItem)
    {
        $isOwner = $marketplaceItem->user_id === auth()->id();
        $isAdmin = auth()->check() && auth()->user()->hasRole(['superadmin', 'admin']);

        if (! $isOwner && ! $isAdmin) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui barang ini.');
        }

        $validated = $request->validated();

        // Jika status bukan 'sold', set status kembali ke 'pending' untuk diverifikasi ulang admin
        $newStatus = $marketplaceItem->status === 'sold' ? 'sold' : 'pending';

        $marketplaceItem->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'meetup_location' => $validated['meetup_location'],
            'city' => $validated['city'] ?? $marketplaceItem->city,
            'description' => $validated['description'],
            'is_featured' => $request->boolean('is_featured'),
            'status' => $newStatus,
        ]);

        if ($request->hasFile('images')) {
            // Hapus gambar lama jika mengunggah gambar baru
            foreach ($marketplaceItem->images as $image) {
                if ($image->image_path && ! str_starts_with($image->image_path, 'http') && ! str_starts_with($image->image_path, 'images/')) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }

            $order = 1;
            foreach ($request->file('images') as $file) {
                $path = $file->store('marketplace', 'public');

                MarketplaceItemImage::create([
                    'marketplace_item_id' => $marketplaceItem->id,
                    'image_path' => $path,
                    'is_primary' => $order === 1,
                    'order' => $order,
                ]);

                $order++;
            }
        }

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        $successMsg = 'Perubahan barang berhasil disimpan! Postingan Anda sedang ditinjau kembali oleh Admin.';

        if ($isTenant) {
            return redirect()->route('tenant.marketplace.my-items')->with('success', $successMsg);
        }

        return redirect()->route('marketplace.index')->with('success', $successMsg);
    }

    /**
     * Hapus barang marketplace yang telah diajukan.
     */
    public function destroy(MarketplaceItem $marketplaceItem)
    {
        // Pastikan hanya pemilik barang atau admin yang dapat menghapus
        $isOwner = $marketplaceItem->user_id === auth()->id();
        $isAdmin = auth()->check() && auth()->user()->hasRole(['superadmin', 'admin']);

        if (! $isOwner && ! $isAdmin) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus barang ini.');
        }

        // Hapus file gambar dari public disk storage
        foreach ($marketplaceItem->images as $image) {
            if ($image->image_path && ! str_starts_with($image->image_path, 'http') && ! str_starts_with($image->image_path, 'images/')) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $title = $marketplaceItem->title;
        $marketplaceItem->delete();

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        if ($isTenant) {
            return redirect()->route('tenant.marketplace.my-items')->with('success', "Barang '{$title}' berhasil dihapus dari Marketplace.");
        }

        return redirect()->route('marketplace.index')->with('success', "Barang '{$title}' berhasil dihapus dari Marketplace.");
    }

    /**
     * Tandai barang sebagai terjual.
     */
    public function markAsSold(MarketplaceItem $marketplaceItem)
    {
        $isOwner = $marketplaceItem->user_id === auth()->id();
        $isAdmin = auth()->check() && auth()->user()->hasRole(['superadmin', 'admin']);

        if (! $isOwner && ! $isAdmin) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status barang ini.');
        }

        $marketplaceItem->update(['status' => 'sold']);

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        if ($isTenant) {
            return redirect()->route('tenant.marketplace.my-items')->with('success', "Status barang '{$marketplaceItem->title}' berhasil diubah menjadi Terjual.");
        }

        return redirect()->route('marketplace.index')->with('success', "Status barang '{$marketplaceItem->title}' berhasil diubah menjadi Terjual.");
    }

    /**
     * Tampilkan detail barang untuk calon pembeli.
     */
    public function show($slug)
    {
        $item = MarketplaceItem::with(['user', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Jika barang belum aktif (pending atau ditolak), hanya pemilik atau admin yang boleh pratinjau
        if ($item->status !== 'active') {
            $isOwner = auth()->check() && $item->user_id === auth()->id();
            $isAdmin = auth()->check() && auth()->user()->hasRole(['superadmin', 'admin']);

            if (! $isOwner && ! $isAdmin) {
                abort(404);
            }
        }

        $item->increment('views_count');

        // Barang terkait dalam kategori yang sama
        $relatedItems = MarketplaceItem::with(['images', 'user'])
            ->where('category', $item->category)
            ->where('id', '!=', $item->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        $isTenant = request()->routeIs('tenant.*') || (auth()->check() && auth()->user()->hasRole('penghuni'));

        if ($isTenant) {
            return view('tenant.marketplace.show', compact('item', 'relatedItems'));
        }

        return view('marketplace.show', compact('item', 'relatedItems'));
    }
}
