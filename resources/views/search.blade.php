<x-seven-layout title="Cari & Filter Kos - Seven Kost">

    {{-- Top Search & Header Bar --}}
    <div class="bg-navy-950 text-white border-b border-slate-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        @if($selectedRegencyName)
                            Kos di {{ $selectedRegencyName }}
                        @elseif($selectedProvinceName)
                            Kos di Provinsi {{ $selectedProvinceName }}
                        @else
                            Jelajah Kos Seluruh Indonesia
                        @endif
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">
                        Menampilkan <span class="text-blue-400 font-bold">{{ $totalCount }}</span> kos sesuai kriteria pencarian Anda
                    </p>
                </div>

                {{-- Fast Search Input & Map Button --}}
                <div class="flex items-center gap-2">
                    <a href="{{ url('/peta') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold text-white border border-white/15 transition shadow-sm">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Buka Mode Peta
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Container (Sidebar Filter + Listing) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ mobileFilterOpen: false }">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- 1. DESKTOP FILTER SIDEBAR --}}
            <aside class="hidden lg:block w-72 flex-shrink-0 sticky top-24">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2 font-bold text-slate-900 text-sm">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter Pencarian
                        </div>
                        <a href="{{ url('/cari') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                            Reset
                        </a>
                    </div>

                    <form action="{{ url('/cari') }}" method="GET" id="filterFormDesktop" class="space-y-5"
                          x-data="{
                              provId: '{{ $selectedProvinceId }}',
                              regId: '{{ $selectedRegencyId }}',
                              regencies: @json($regencies),
                              isLoading: false,
                              async fetchRegencies() {
                                  this.regId = '';
                                  this.regencies = [];
                                  if (!this.provId) return;
                                  this.isLoading = true;
                                  try {
                                      const res = await fetch(`/api/wilayah/provinces/${this.provId}/regencies`);
                                      this.regencies = await res.json();
                                  } catch (e) {
                                      console.error(e);
                                  } finally {
                                      this.isLoading = false;
                                  }
                              }
                          }">

                        {{-- Keyword Search --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Kunci</label>
                            <input type="text" name="q" value="{{ $keyword }}" placeholder="Nama kos / area..." class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2">
                        </div>

                        {{-- Provinsi Dropdown --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Provinsi</label>
                            <select name="provinsi" x-model="provId" @change="fetchRegencies()" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kota / Kabupaten Dropdown --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kabupaten / Kota</label>
                            <select name="kota" x-model="regId" :disabled="!provId || isLoading" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 disabled:opacity-50">
                                <option value="" x-text="isLoading ? 'Memuat...' : 'Semua Kota/Kab'"></option>
                                <template x-for="reg in regencies" :key="reg.id">
                                    <option :value="reg.id" x-text="reg.name" :selected="reg.id == '{{ $selectedRegencyId }}'"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Tipe Kos Radio --}}
                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tipe Kos</label>
                            <div class="space-y-1.5">
                                <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                                    <input type="radio" name="tipe" value="" {{ empty($selectedType) ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                    <span>Semua Tipe</span>
                                </label>
                                <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                                    <input type="radio" name="tipe" value="putri" {{ $selectedType === 'putri' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                    <span>Khusus Putri</span>
                                </label>
                                <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                                    <input type="radio" name="tipe" value="putra" {{ $selectedType === 'putra' ? 'checked' : '' }} class="text-sky-600 focus:ring-sky-500">
                                    <span>Khusus Putra</span>
                                </label>
                                <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                                    <input type="radio" name="tipe" value="campur" {{ $selectedType === 'campur' ? 'checked' : '' }} class="text-purple-600 focus:ring-purple-500">
                                    <span>Kos Campur</span>
                                </label>
                            </div>
                        </div>

                        {{-- Rentang Harga --}}
                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Harga per Bulan</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min (Rp)" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-1.5">
                                </div>
                                <div>
                                    <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max (Rp)" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-1.5">
                                </div>
                            </div>
                        </div>

                        {{-- Fasilitas Checkbox --}}
                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Fasilitas Utama</label>
                            <div class="space-y-1.5">
                                @php
                                    $facilitiesList = [
                                        'ac' => 'AC',
                                        'wifi' => 'WiFi Cepat',
                                        'kamar mandi dalam' => 'Kamar Mandi Dalam',
                                        'parkir mobil' => 'Parkir Mobil',
                                        'kasur' => 'Kasur / Springbed',
                                        'dapur bersama' => 'Dapur Bersama',
                                    ];
                                @endphp
                                @foreach($facilitiesList as $val => $label)
                                    <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="fasilitas[]" value="{{ $val }}" {{ in_array($val, $selectedFacilities) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Hidden Sort --}}
                        <input type="hidden" name="sort" value="{{ $sort }}">

                        {{-- Tombol Terapkan --}}
                        <div class="pt-2">
                            <x-seven-button type="submit" variant="primary" size="sm" class="w-full">
                                Terapkan Filter
                            </x-seven-button>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- 2. LISTING RESULTS & CONTROLS --}}
            <div class="flex-1 w-full">
                {{-- Control Bar (Active Filter Pills, Mobile Filter Trigger & Sort Dropdown) --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 mb-6 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    {{-- Mobile Filter Trigger --}}
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-between">
                        <button @click="mobileFilterOpen = true" type="button" class="lg:hidden inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-800 transition">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filter Kos
                            @if($selectedProvinceId || $selectedType || $minPrice || $maxPrice || !empty($selectedFacilities))
                                <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                            @endif
                        </button>

                        <span class="text-xs text-slate-500 font-medium">
                            Ditemukan <strong class="text-slate-900">{{ $totalCount }}</strong> kos
                        </span>
                    </div>

                    {{-- Sort Dropdown --}}
                    <form action="{{ url('/cari') }}" method="GET" class="flex items-center gap-2 self-end sm:self-auto w-full sm:w-auto justify-end">
                        {{-- Keep other query params --}}
                        @if($keyword)<input type="hidden" name="q" value="{{ $keyword }}">@endif
                        @if($selectedProvinceId)<input type="hidden" name="provinsi" value="{{ $selectedProvinceId }}">@endif
                        @if($selectedRegencyId)<input type="hidden" name="kota" value="{{ $selectedRegencyId }}">@endif
                        @if($selectedType)<input type="hidden" name="tipe" value="{{ $selectedType }}">@endif
                        @if($minPrice)<input type="hidden" name="min_price" value="{{ $minPrice }}">@endif
                        @if($maxPrice)<input type="hidden" name="max_price" value="{{ $maxPrice }}">@endif
                        @foreach($selectedFacilities as $f)
                            <input type="hidden" name="fasilitas[]" value="{{ $f }}">
                        @endforeach

                        <label for="sortSelector" class="text-xs text-slate-500 font-medium whitespace-nowrap">Urutkan:</label>
                        <select id="sortSelector" name="sort" onchange="this.form.submit()" class="text-xs font-semibold rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-1.5 pr-8">
                            <option value="unggulan" {{ $sort === 'unggulan' ? 'selected' : '' }}>🌟 Rekomendasi / Unggulan</option>
                            <option value="termurah" {{ $sort === 'termurah' ? 'selected' : '' }}>💵 Harga Termurah</option>
                            <option value="termahal" {{ $sort === 'termahal' ? 'selected' : '' }}>💎 Harga Tertinggi</option>
                            <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>⚡ Paling Baru</option>
                        </select>
                    </form>
                </div>

                {{-- Active Filter Badges / Pills --}}
                @if($selectedProvinceName || $selectedRegencyName || $selectedType || $minPrice || $maxPrice || !empty($selectedFacilities))
                    <div class="flex items-center gap-2 flex-wrap mb-6">
                        <span class="text-xs text-slate-400 font-medium">Filter Aktif:</span>

                        @if($selectedProvinceName)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-xs font-semibold text-blue-700">
                                Prov: {{ $selectedProvinceName }}
                            </span>
                        @endif

                        @if($selectedRegencyName)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-xs font-semibold text-blue-700">
                                {{ $selectedRegencyName }}
                            </span>
                        @endif

                        @if($selectedType)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-50 border border-purple-200/80 text-xs font-semibold text-purple-700 uppercase">
                                Tipe: {{ $selectedType }}
                            </span>
                        @endif

                        @if($maxPrice)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/80 text-xs font-semibold text-emerald-700">
                                Maks: Rp {{ number_format($maxPrice, 0, ',', '.') }}
                            </span>
                        @endif

                        <a href="{{ url('/cari') }}" class="text-xs text-red-500 hover:text-red-700 font-semibold underline ml-1">
                            Hapus Semua
                        </a>
                    </div>
                @endif

                {{-- Result Grid --}}
                @if($totalCount > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($kosts as $kost)
                            <x-kost-card 
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
                            />
                        @endforeach
                    </div>
                @else
                    {{-- Empty State (Sesuai Catatan Jadwal BR-19) --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto shadow-xs my-8 space-y-4">
                        <div class="h-20 w-20 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                            <svg class="h-10 w-10 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Belum Ada Kos di Wilayah Ini</h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Kami belum menemukan kos yang cocok dengan kriteria filter yang Anda tentukan. Coba kurangi filter atau reset pencarian.
                        </p>
                        <div class="pt-2 flex justify-center gap-3">
                            <x-seven-button href="{{ url('/cari') }}" variant="secondary" size="sm">
                                Reset Filter
                            </x-seven-button>
                            <x-seven-button href="{{ url('/peta') }}" variant="primary" size="sm">
                                Cari Lewat Peta
                            </x-seven-button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- 3. MOBILE FILTER DRAWER (MODAL) --}}
        <div x-show="mobileFilterOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs lg:hidden" style="display: none;">
            <div @click.outside="mobileFilterOpen = false" class="fixed inset-y-0 right-0 w-full max-w-xs bg-white p-6 shadow-2xl overflow-y-auto space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Filter Kos</h3>
                    <button @click="mobileFilterOpen = false" class="text-slate-400 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Form mobile --}}
                <form action="{{ url('/cari') }}" method="GET" class="space-y-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Kata Kunci</label>
                        <input type="text" name="q" value="{{ $keyword }}" placeholder="Nama kos..." class="w-full text-xs rounded-xl border-slate-200">
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
                        <x-seven-button href="{{ url('/cari') }}" variant="secondary" size="sm" class="flex-1">
                            Reset
                        </x-seven-button>
                        <x-seven-button type="submit" variant="primary" size="sm" class="flex-1">
                            Terapkan
                        </x-seven-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-seven-layout>
