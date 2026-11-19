<x-owner-layout :title="'Kelola Kamar - ' . $kost->name">

    <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        {{-- Header Card (Refined Sapphire Blue Gradient) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #0f3d7c 0%, #1b5cb8 50%, #2563eb 100%);">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-blue-200 font-medium mb-2">
                        <a href="{{ route('owner.dashboard') }}" class="hover:underline">Dasbor Pemilik</a>
                        <span>/</span>
                        <a href="{{ route('owner.kosts.index') }}" class="hover:underline">Kelola Iklan</a>
                        <span>/</span>
                        <span class="text-white font-semibold">Kelola Kamar</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Manajemen Kamar: {{ $kost->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100/90 mt-1">
                        Atur variasi tipe kamar, tarif bulanan, serta perbarui stok ketersediaan unit siap huni secara langsung.
                    </p>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('owner.kosts.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-medium transition">
                        &larr; Kembali ke Iklan
                    </a>
                    <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-medium transition">
                        Ke Dasbor
                    </a>
                </div>
            </div>
        </div>

        <div 
            x-data="{ 
                showAddModal: false, 
                showEditModal: false,
                editForm: {
                    id: null,
                    name: '',
                    price: 0,
                    total_rooms: 1,
                    available_rooms: 0,
                    room_size: '',
                    facilities: [],
                    actionUrl: ''
                },
                openEdit(room, url) {
                    this.editForm.id = room.id;
                    this.editForm.name = room.name;
                    this.editForm.price = room.price;
                    this.editForm.total_rooms = room.total_rooms;
                    this.editForm.available_rooms = room.available_rooms;
                    this.editForm.room_size = room.room_size || '';
                    this.editForm.facilities = room.facilities ? room.facilities.map(f => f.id) : [];
                    this.editForm.actionUrl = url;
                    this.showEditModal = true;
                }
            }"
        >
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-emerald-800 flex items-center gap-2.5 text-xs sm:text-sm font-semibold shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-2xl bg-red-50 border border-red-200/80 p-4 text-red-800 shadow-xs text-xs sm:text-sm">
                <strong class="font-bold block mb-1">Terjadi kesalahan pada data kamar:</strong>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Top Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Tipe Kamar</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-1 block tabular-nums">{{ $rooms->count() }} Tipe</span>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Kapasitas Unit</span>
                <span class="text-3xl font-extrabold text-blue-600 mt-1 block tabular-nums">{{ $rooms->sum('total_rooms') }} Kamar</span>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Kamar Kosong / Siap Huni</span>
                    <span class="text-3xl font-extrabold text-emerald-600 mt-1 block tabular-nums">{{ $rooms->sum('available_rooms') }} Kamar</span>
                </div>
                <button @click="showAddModal = true" class="h-10 w-10 rounded-xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center shadow-xs transition" title="Tambah Tipe Kamar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>
        </div>

        {{-- Room List Table/Grid --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftar Tipe Kamar</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Tipe kamar yang aktif ditawarkan pada listing properti kos ini.</p>
                </div>
                <x-seven-button @click="showAddModal = true" variant="primary" size="sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Tipe Kamar
                </x-seven-button>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($rooms as $room)
                    <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/60 transition">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-extrabold text-sm sm:text-base text-slate-900 font-sans">{{ $room->name }}</h3>
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $room->room_size ?: 'Ukuran Standar' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium">
                                Total Kapasitas: <strong class="text-slate-800 tabular-nums">{{ $room->total_rooms }} kamar</strong> &bull; 
                                Tersedia Siap Huni: <strong class="text-emerald-600 tabular-nums font-bold">{{ $room->available_rooms }} kamar</strong>
                            </p>

                            @if($room->facilities->isNotEmpty())
                                <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                    @foreach($room->facilities as $fac)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50/80 text-blue-700 border border-blue-100">
                                            <svg class="w-2.5 h-2.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            {{ $fac->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 sm:gap-6 sm:self-center">
                            <div class="text-right">
                                <span class="text-base sm:text-lg font-extrabold text-blue-600 block tabular-nums">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-slate-400">per bulan</span>
                            </div>

                            <div class="flex items-center gap-1.5">
                                {{-- Edit Button --}}
                                <button 
                                    type="button"
                                    @click="openEdit({{ json_encode($room) }}, '{{ route('owner.rooms.update', [$kost, $room]) }}')"
                                    class="p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition border border-slate-200"
                                    title="Edit Tipe & Ketersediaan Kamar"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>

                                {{-- Delete Button --}}
                                <form action="{{ route('owner.rooms.destroy', [$kost, $room]) }}" method="POST" onsubmit="return confirm('Hapus tipe kamar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition border border-slate-200" title="Hapus Kamar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-xs text-slate-400">
                        Belum ada tipe kamar yang didaftarkan. Klik tombol "Tambah Tipe Kamar" di atas.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Modal Tambah Kamar Baru --}}
        <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
            <div @click.outside="showAddModal = false" class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl space-y-4 text-slate-800 border border-slate-200/90">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div>
                        <h3 class="font-semibold text-base text-slate-900 tracking-tight">Tambah Tipe Kamar Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftarkan varian kamar beserta kapasitas unit siap huni.</p>
                    </div>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('owner.rooms.store', $kost) }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label for="new_room_name" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Nama Tipe Kamar <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="new_room_name"
                            name="name" 
                            placeholder="Contoh: Kamar VIP King, Tipe B Lantai 2" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div>
                        <label for="new_room_price" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Tarif Sewa / Bulan (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="new_room_price"
                            name="price" 
                            value="1200000" 
                            min="100000" 
                            step="50000" 
                            required 
                            class="w-full text-sm font-medium rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-3.5">
                        <div>
                            <label for="new_total_rooms" class="block text-[13px] font-medium text-slate-700 mb-1">
                                Total Unit <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="new_total_rooms"
                                name="total_rooms" 
                                value="4" 
                                min="1" 
                                required 
                                class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                            >
                        </div>
                        <div>
                            <label for="new_available_rooms" class="block text-[13px] font-medium text-slate-700 mb-1">
                                Unit Siap Huni <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="new_available_rooms"
                                name="available_rooms" 
                                value="2" 
                                min="0" 
                                required 
                                class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="new_room_size" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Ukuran Dimensi Kamar (Opsional)
                        </label>
                        <input 
                            type="text" 
                            id="new_room_size"
                            name="room_size" 
                            placeholder="Contoh: 3 x 4 meter" 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-slate-700 mb-1.5">
                            Fasilitas Khusus Tipe Kamar Ini
                        </label>
                        <div class="grid grid-cols-2 gap-2 p-3 bg-slate-50/80 rounded-xl border border-slate-200/90 max-h-44 overflow-y-auto">
                            @foreach($roomFacilities as $rf)
                                <label class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white text-xs text-slate-700 cursor-pointer transition select-none">
                                    <input type="checkbox" name="facilities[]" value="{{ $rf->id }}" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="truncate">{{ $rf->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Centang fasilitas yang tersedia di dalam unit kamar ini.</p>
                    </div>

                    <div class="pt-2.5 flex items-center gap-2.5">
                        <button type="button" @click="showAddModal = false" class="flex-1 py-2 rounded-lg border border-slate-300 text-[13px] font-medium text-slate-700 hover:bg-slate-50 transition cursor-pointer shadow-2xs">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-[13px] font-medium shadow-xs transition cursor-pointer">
                            Simpan Kamar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit Kamar --}}
        <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
            <div @click.outside="showEditModal = false" class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl space-y-4 text-slate-800 border border-slate-200/90">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div>
                        <h3 class="font-semibold text-base text-slate-900 tracking-tight">Perbarui Tipe & Ketersediaan Kamar</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Sesuaikan tarif, kuota total kamar, dan ketersediaan unit kosong.</p>
                    </div>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editForm.actionUrl" method="POST" class="space-y-3.5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[13px] font-medium text-slate-700 mb-1">
                            Nama Tipe Kamar <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="editForm.name" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-slate-700 mb-1">
                            Tarif Sewa / Bulan (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="price" 
                            x-model="editForm.price" 
                            min="100000" 
                            step="50000" 
                            required 
                            class="w-full text-sm font-medium rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[13px] font-medium text-slate-700 mb-1">
                                Total Unit <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="total_rooms" 
                                x-model="editForm.total_rooms" 
                                min="1" 
                                required 
                                class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                            >
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-slate-700 mb-1">
                                Unit Siap Huni <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="available_rooms" 
                                x-model="editForm.available_rooms" 
                                min="0" 
                                required 
                                class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-slate-700 mb-1">
                            Ukuran Dimensi Kamar (Opsional)
                        </label>
                        <input 
                            type="text" 
                            name="room_size" 
                            x-model="editForm.room_size" 
                            placeholder="Contoh: 3 x 4 meter" 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-slate-700 mb-1.5">
                            Fasilitas Khusus Tipe Kamar Ini
                        </label>
                        <div class="grid grid-cols-2 gap-2 p-3 bg-slate-50/80 rounded-xl border border-slate-200/90 max-h-44 overflow-y-auto">
                            @foreach($roomFacilities as $rf)
                                <label class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white text-xs text-slate-700 cursor-pointer transition select-none">
                                    <input type="checkbox" name="facilities[]" :value="{{ $rf->id }}" x-model="editForm.facilities" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="truncate">{{ $rf->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Centang fasilitas yang tersedia di dalam unit kamar ini.</p>
                    </div>

                    <div class="pt-2.5 flex items-center gap-2.5">
                        <button type="button" @click="showEditModal = false" class="flex-1 py-2 rounded-lg border border-slate-300 text-[13px] font-medium text-slate-700 hover:bg-slate-50 transition cursor-pointer shadow-2xs">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-[13px] font-medium shadow-xs transition cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        </div>

    </div>

</x-owner-layout>
