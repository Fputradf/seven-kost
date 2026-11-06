<x-seven-layout title="Peta Pencarian Kos Seluruh Indonesia - Seven Kost">

    @push('styles')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        {{-- Leaflet MarkerCluster CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
        <style>
            .map-container {
                height: calc(100vh - 4.5rem);
                width: 100%;
            }
            .leaflet-popup-content-wrapper {
                border-radius: 1.25rem !important;
                padding: 0 !important;
                overflow: hidden !important;
                box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
            }
            .leaflet-popup-content {
                margin: 0 !important;
                width: 260px !important;
            }
            .marker-cluster-small, .marker-cluster-medium, .marker-cluster-large {
                background-color: rgba(30, 58, 138, 0.4) !important;
            }
            .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div {
                background-color: rgb(30, 58, 138) !important;
                color: white !important;
                font-weight: bold !important;
            }
        </style>
    @endpush

    <div class="relative w-full overflow-hidden" 
         x-data="{
             provId: '{{ $selectedProvinceId }}',
             regId: '{{ $selectedRegencyId }}',
             regencies: @json($regencies),
             isLoading: false,
             filterOpen: true,
             async onProvChange() {
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

        {{-- Floating Control / Filter Card --}}
        <div class="absolute top-4 left-4 right-4 sm:right-auto sm:w-96 z-20">
            <div class="bg-white/95 backdrop-blur-md rounded-3xl border border-slate-200/80 shadow-2xl p-4 sm:p-5 text-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h2 class="text-sm font-extrabold text-slate-900">Peta Kos Nasional</h2>
                    </div>
                    <a href="{{ url('/cari') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Mode Daftar
                    </a>
                </div>

                {{-- Filter Form --}}
                <form action="{{ url('/peta') }}" method="GET" class="space-y-3">
                    {{-- Provinsi --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase">Provinsi</label>
                        <select name="provinsi" x-model="provId" @change="onProvChange()" class="w-full text-xs font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-2">
                            <option value="">Semua Provinsi</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kab / Kota --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase">Kota / Kabupaten</label>
                        <select name="kota" x-model="regId" :disabled="!provId || isLoading" class="w-full text-xs font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-2 disabled:opacity-50">
                            <option value="" x-text="isLoading ? 'Memuat wilayah...' : 'Semua Kab/Kota'"></option>
                            <template x-for="reg in regencies" :key="reg.id">
                                <option :value="reg.id" x-text="reg.name" :selected="reg.id == regId"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Tipe Kos --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase">Tipe Kos</label>
                        <select name="tipe" class="w-full text-xs font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-2">
                            <option value="">Semua Tipe</option>
                            <option value="putra" {{ $selectedType === 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                            <option value="putri" {{ $selectedType === 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                            <option value="campur" {{ $selectedType === 'campur' ? 'selected' : '' }}>Kos Campur</option>
                        </select>
                    </div>

                    <div class="pt-1 flex gap-2">
                        <a href="{{ url('/peta') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 text-center flex-1">
                            Reset
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 text-center flex-1">
                            Filter Peta
                        </button>
                    </div>
                </form>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Total pin terlihat:</span>
                    <strong class="text-blue-600 font-extrabold">{{ $kosts->count() }} Kos</strong>
                </div>
            </div>
        </div>

        {{-- Leaflet Map Div --}}
        <div id="fullSearchMap" class="map-container"></div>
    </div>

    @push('scripts')
        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        {{-- Leaflet MarkerCluster JS --}}
        <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const kostsData = @json($kosts);

                // Pusat peta Indonesia default
                const defaultCenter = [-2.5489, 118.0149];
                const defaultZoom = 5;

                const map = L.map('fullSearchMap', {
                    zoomControl: false
                }).setView(defaultCenter, defaultZoom);

                // Tambahkan kontrol zoom di kanan bawah agar tidak tertutup card
                L.control.zoom({ position: 'bottomright' }).addTo(map);

                // Tile Layer OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors | Seven Kost'
                }).addTo(map);

                // Buat Marker Cluster Group
                const markersGroup = L.markerClusterGroup({
                    chunkedLoading: true,
                    spiderfyOnMaxZoom: true,
                    showCoverageOnHover: false,
                    zoomToBoundsOnClick: true
                });

                const latLngList = [];

                kostsData.forEach(function (item) {
                    if (item.lat && item.lng) {
                        const marker = L.marker([item.lat, item.lng]);

                        const popupHtml = `
                            <div class="flex flex-col">
                                <div class="relative aspect-video w-full overflow-hidden bg-slate-100">
                                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-white/90 text-blue-700 shadow-xs">
                                        ${item.type}
                                    </span>
                                </div>
                                <div class="p-3.5 space-y-2">
                                    <div>
                                        <span class="text-[10px] font-medium text-slate-400 block">${item.city}</span>
                                        <h4 class="font-bold text-xs text-slate-900 leading-snug line-clamp-1">${item.name}</h4>
                                    </div>
                                    <div class="flex items-center justify-between pt-1 border-t border-slate-100">
                                        <div>
                                            <span class="text-[9px] text-slate-400 block">Mulai dari</span>
                                            <span class="text-xs font-black text-blue-600">${item.price_formatted}</span>
                                        </div>
                                        <a href="${item.url}" class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold transition">
                                            Detail &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;

                        marker.bindPopup(popupHtml);
                        markersGroup.addLayer(marker);
                        latLngList.push([item.lat, item.lng]);
                    }
                });

                map.addLayer(markersGroup);

                // Jika ada titik kos, sesuaikan zoom dan bounds otomatis
                if (latLngList.length > 0) {
                    map.fitBounds(latLngList, { padding: [50, 50], maxZoom: 14 });
                }
            });
        </script>
    @endpush

</x-seven-layout>
