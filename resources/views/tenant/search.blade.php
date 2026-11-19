<x-tenant-layout title="Cari Kamar Kos" :backUrl="route('tenant.dashboard')">

    <div class="space-y-4 pb-12" x-data="{ filterOpen: false }">

        {{-- Top Gradient Header Banner --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 flex items-center justify-between gap-3" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div>
                <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider block">Eksplorasi Kos</span>
                <h1 class="text-base sm:text-lg font-black text-white !text-white leading-tight">
                    @if($selectedRegencyName)
                        Kos di {{ $selectedRegencyName }}
                    @elseif($selectedProvinceName)
                        Kos di {{ $selectedProvinceName }}
                    @else
                        Cari Kamar Kos Impian
                    @endif
                </h1>
                <p class="text-xs text-white/90 mt-0.5">Temukan kos nyaman sesuai kebutuhan Anda</p>
            </div>
            <a href="{{ route('tenant.map') }}" class="shrink-0 px-3 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition border border-white/30 active:scale-95 flex items-center gap-1 shadow-xs">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span>Peta</span>
            </a>
        </div>

        {{-- Fast Search Input & Filter Trigger --}}
        <div class="flex items-center gap-2">
            <form action="{{ route('tenant.search') }}" method="GET" class="flex-1 relative">
                @if($selectedProvinceId)<input type="hidden" name="provinsi" value="{{ $selectedProvinceId }}">@endif
                @if($selectedRegencyId)<input type="hidden" name="kota" value="{{ $selectedRegencyId }}">@endif
                @if($selectedType)<input type="hidden" name="tipe" value="{{ $selectedType }}">@endif
                @if($minPrice)<input type="hidden" name="min_price" value="{{ $minPrice }}">@endif
                @if($maxPrice)<input type="hidden" name="max_price" value="{{ $maxPrice }}">@endif
                @if($sort)<input type="hidden" name="sort" value="{{ $sort }}">@endif

                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari nama atau area kos..." class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-2xs">
                </div>
            </form>

            <button @click="filterOpen = true" type="button" class="shrink-0 p-2.5 bg-white border border-slate-200 hover:border-blue-300 rounded-xl text-slate-700 hover:text-blue-600 shadow-2xs transition flex items-center gap-1.5 active:scale-95" title="Filter Kos">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span class="text-xs font-bold hidden xs:inline">Filter</span>
                @if($selectedProvinceId || $selectedType || $minPrice || $maxPrice || !empty($selectedFacilities))
                    <span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                @endif
            </button>
        </div>

        {{-- Active Filter Pills --}}
        @if($selectedProvinceName || $selectedRegencyName || $selectedType || $minPrice || $maxPrice || !empty($selectedFacilities))
            <div class="flex items-center gap-1.5 flex-wrap pt-1">
                <span class="text-[11px] text-slate-400 font-medium">Filter:</span>

                @if($selectedProvinceName)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-[11px] font-semibold">
                        {{ $selectedProvinceName }}
                    </span>
                @endif

                @if($selectedRegencyName)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-[11px] font-semibold">
                        {{ $selectedRegencyName }}
                    </span>
                @endif

                @if($selectedType)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 text-[11px] font-semibold">
                        Kos {{ ucfirst($selectedType) }}
                    </span>
                @endif

                @if($maxPrice)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-semibold">
                        ≤ Rp {{ number_format($maxPrice, 0, ',', '.') }}
                    </span>
                @endif

                <a href="{{ route('tenant.search') }}" class="text-[11px] text-rose-600 hover:text-rose-700 font-bold underline ml-1">
                    Reset
                </a>
            </div>
        @endif

        {{-- Counter & Sort Bar --}}
        <div class="flex items-center justify-between gap-2 pt-1 pb-1">
            <span class="text-xs text-slate-500 font-medium">
                Ditemukan <strong class="text-slate-900 font-bold">{{ $totalCount }}</strong> kos
            </span>

            {{-- Sort Selector --}}
            <form action="{{ route('tenant.search') }}" method="GET" class="flex items-center gap-1.5">
                @if($keyword)<input type="hidden" name="q" value="{{ $keyword }}">@endif
                @if($selectedProvinceId)<input type="hidden" name="provinsi" value="{{ $selectedProvinceId }}">@endif
                @if($selectedRegencyId)<input type="hidden" name="kota" value="{{ $selectedRegencyId }}">@endif
                @if($selectedType)<input type="hidden" name="tipe" value="{{ $selectedType }}">@endif
                @if($minPrice)<input type="hidden" name="min_price" value="{{ $minPrice }}">@endif
                @if($maxPrice)<input type="hidden" name="max_price" value="{{ $maxPrice }}">@endif

                <select name="sort" onchange="this.form.submit()" class="text-xs font-semibold rounded-xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 py-1.5 pl-2.5 pr-7 shadow-2xs">
                    <option value="unggulan" {{ $sort === 'unggulan' ? 'selected' : '' }}>Rekomendasi</option>
                    <option value="termurah" {{ $sort === 'termurah' ? 'selected' : '' }}>Termurah</option>
                    <option value="termahal" {{ $sort === 'termahal' ? 'selected' : '' }}>Tertinggi</option>
                    <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                </select>
            </form>
        </div>

        {{-- Results Feed --}}
        @if($totalCount > 0)
            <div class="space-y-4">
                @foreach($kosts as $kost)
                    <x-kost-card 
                        :id="$kost['id']"
                        :title="$kost['title']"
                        :slug="$kost['slug']"
                        :type="$kost['type']"
                        :city="$kost['city']"
                        :province="$kost['province']"
                        :price="$kost['price']"
                        :rentType="$kost['rentType']"
                        :isVerified="$kost['isVerified']"
                        :isFeatured="$kost['isFeatured']"
                        :availableRooms="$kost['availableRooms']"
                        :facilities="$kost['facilities']"
                        :image="$kost['image']"
                        :isFavorited="$kost['isFavorited'] ?? false"
                        :detailUrl="route('tenant.kost.detail', $kost['slug'])"
                    />
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-8 text-center space-y-3 shadow-2xs">
                <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Belum Ada Kos Ditemukan</h3>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                    Tidak ada kos yang cocok dengan kriteria filter saat ini. Coba kurangi filter atau cari dengan kata kunci lain.
                </p>
                <div class="pt-2 flex justify-center gap-2">
                    <a href="{{ route('tenant.search') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Reset Filter
                    </a>
                    <a href="{{ route('tenant.map') }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition">
                        Cari di Peta
                    </a>
                </div>
            </div>
        @endif

        {{-- Mobile Filter Drawer (Modal Sheet) --}}
        <div x-show="filterOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex justify-end" style="display: none;">
            <div @click.outside="filterOpen = false" class="w-full max-w-xs bg-white h-full p-5 shadow-2xl overflow-y-auto space-y-5 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2 font-bold text-slate-900 text-sm">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter Kos
                        </div>
                        <button @click="filterOpen = false" class="text-slate-400 hover:text-slate-700 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ route('tenant.search') }}" method="GET" class="space-y-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Kata Kunci</label>
                            <input type="text" name="q" value="{{ $keyword }}" placeholder="Nama kos atau lokasi..." class="w-full text-xs rounded-xl border-slate-200">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Provinsi</label>
                            <select name="provinsi" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->id }}" {{ $prov->id == $selectedProvinceId ? 'selected' : '' }}>{{ $prov->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Tipe Kos</label>
                            <select name="tipe" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="">Semua Tipe</option>
                                <option value="putri" {{ $selectedType === 'putri' ? 'selected' : '' }}>Putri</option>
                                <option value="putra" {{ $selectedType === 'putra' ? 'selected' : '' }}>Putra</option>
                                <option value="campur" {{ $selectedType === 'campur' ? 'selected' : '' }}>Campur</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Maksimal Harga</label>
                            <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Maks. Rp" class="w-full text-xs rounded-xl border-slate-200">
                        </div>

                        <div class="pt-4 flex gap-2">
                            <a href="{{ route('tenant.search') }}" class="flex-1 text-center py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                Reset
                            </a>
                            <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs">
                                Terapkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</x-tenant-layout>
