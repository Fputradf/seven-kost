{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/cari') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/peta') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/paket-iklan') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @foreach($provinces as $province)
        @foreach($province->regencies as $regency)
            <url>
                <loc>{{ url('/kos/' . \Illuminate\Support\Str::slug($province->name) . '/' . \Illuminate\Support\Str::slug($regency->name)) }}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endforeach
    @foreach($verifiedKosts as $kost)
        <url>
            <loc>{{ url('/kos/' . $kost->slug) }}</loc>
            <lastmod>{{ $kost->updated_at ? $kost->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>
