<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarketplaceItemRequest;
use App\Models\Banner;
use App\Models\MarketplaceItem;
use App\Models\MarketplaceItemImage;
use Illuminate\Http\Request;
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
            'status' => 'active',
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

        return redirect()->route('marketplace.index')->with('success', 'Barang Anda berhasil diposting di Marketplace SevenKost! Pembeli akan menghubungi Anda untuk transaksi COD.');
    }

    /**
     * Tampilkan detail barang untuk calon pembeli.
     */
    public function show($slug)
    {
        $item = MarketplaceItem::with(['user', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        $item->increment('views_count');

        // Barang terkait dalam kategori yang sama
        $relatedItems = MarketplaceItem::with(['images', 'user'])
            ->where('category', $item->category)
            ->where('id', '!=', $item->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('item', 'relatedItems'));
    }
}
