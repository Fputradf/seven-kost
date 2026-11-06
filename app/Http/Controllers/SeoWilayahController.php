<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SeoWilayahController extends Controller
{
    /**
     * Tampilkan landing page SEO regional per Kabupaten/Kota (/kos/{provinsi}/{kota}).
     */
    public function show(string $provinsiSlug, string $kotaSlug): View
    {
        // Cache data wilayah selama 24 jam untuk kecepatan respon maksimal
        $provinces = Cache::remember('all_provinces_with_regencies', 86400, function () {
            return Province::with('regencies')->get();
        });

        $province = $provinces->first(function ($p) use ($provinsiSlug) {
            return Str::slug($p->name) === $provinsiSlug;
        });

        if (!$province) {
            abort(404, 'Provinsi tidak ditemukan');
        }

        $regency = $province->regencies->first(function ($r) use ($kotaSlug) {
            return Str::slug($r->name) === $kotaSlug;
        });

        if (!$regency) {
            abort(404, 'Kabupaten/Kota tidak ditemukan');
        }

        // Ambil daftar kos aktif & terverifikasi di wilayah ini
        $kosts = Kost::with(['province', 'regency', 'facilities', 'rooms', 'primaryImage'])
            ->where('regency_id', $regency->id)
            ->where('is_available', true)
            ->orderBy('is_featured', 'desc')
            ->latest()
            ->paginate(12);

        // Hitung statistik untuk konten dinamis dan FAQ
        $totalKos = $kosts->total();
        $minPrice = Kost::where('regency_id', $regency->id)->where('is_available', true)->min('price_start') ?? 500000;
        $maxPrice = Kost::where('regency_id', $regency->id)->where('is_available', true)->max('price_start') ?? 3000000;

        // Konten tipis / wilayah kosong diberi noindex sesuai BR-19
        $robots = $totalKos > 0 ? 'index, follow' : 'noindex, follow';

        // Rekomendasi kota lain di provinsi yang sama untuk internal linking SEO
        $siblingRegencies = $province->regencies
            ->where('id', '!=', $regency->id)
            ->take(6);

        // Skema JSON-LD Breadcrumbs
        $breadcrumbsSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => url('/'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Cari Kos',
                    'item' => url('/cari'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $province->name,
                    'item' => url('/cari?provinsi=' . $province->id),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 4,
                    'name' => 'Kos ' . $regency->name,
                    'item' => url('/kos/' . $provinsiSlug . '/' . $kotaSlug),
                ],
            ],
        ];

        // Skema JSON-LD FAQ
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => "Berapa rata-rata harga sewa kos di {$regency->name}?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Harga sewa kos di {$regency->name} berkisar mulai dari Rp " . number_format($minPrice, 0, ',', '.') . " hingga Rp " . number_format($maxPrice, 0, ',', '.') . " per bulan, bervariasi menurut fasilitas seperti kamar mandi dalam, AC, WiFi, dan kedekatan dengan fasilitas umum.",
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => "Bagaimana cara booking kos di {$regency->name} lewat Seven Kost?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Anda dapat memilih kamar kos yang diinginkan, menekan tombol Ajukan Sewa / Booking untuk reservasi langsung, atau menghubungi nomor WhatsApp pemilik yang tertera secara transparan tanpa biaya komisi perantara.",
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => "Apakah kos di {$regency->name} terverifikasi?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Kos dengan lencana centang 'Terverifikasi' telah diverifikasi secara manual oleh tim Seven Kost meliputi keaslian foto, kejelasan alamat, dan nomor kontak pemilik untuk kenyamanan pencari kos.",
                    ],
                ],
            ],
        ];

        return view('seo-wilayah', compact(
            'province',
            'regency',
            'kosts',
            'totalKos',
            'minPrice',
            'maxPrice',
            'robots',
            'siblingRegencies',
            'provinsiSlug',
            'kotaSlug',
            'breadcrumbsSchema',
            'faqSchema'
        ));
    }

    /**
     * Generate sitemap.xml otomatis untuk Googlebot dan crawler mesin pencari.
     */
    public function sitemap(): Response
    {
        $provinces = Province::with('regencies')->get();
        $verifiedKosts = Kost::where('is_available', true)->latest()->take(500)->get();

        $content = view('sitemap', compact('provinces', 'verifiedKosts'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
