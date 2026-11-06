<x-seven-layout :title="'Kelola Kamar - ' . $kost->name">

    <div class="bg-navy-950 text-white py-10 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-blue-400 font-semibold mb-2">
                        <a href="{{ route('owner.kosts.index') }}" class="hover:underline">Kelola Iklan Saya</a>
                        <span>/</span>
                        <span class="text-slate-300">Kelola Kamar</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Manajemen Kamar: {{ $kost->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">
                        Atur variasi tipe kamar, tarif bulanan, serta ketersediaan unit siap huni.
                    </p>
                </div>

                <a href="{{ route('owner.kosts.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-white/20 text-xs font-semibold text-white hover:bg-white/10 transition">
                    &larr; Kembali ke Daftar Iklan
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showAddModal: false }">
        {{-- Flash message --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-center gap-2 text-xs font-semibold">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Top Stats & Add Room Trigger --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
                <span class="text-xs font-medium text-slate-400 block">Total Tipe Kamar</span>
                <span class="text-3xl font-black text-slate-900 mt-1 block">{{ $rooms->count() }} Tipe</span>
            </div>
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
                <span class="text-xs font-medium text-slate-400 block">Total Kapasitas Unit</span>
                <span class="text-3xl font-black text-blue-600 mt-1 block">{{ $rooms->sum('total_rooms') }} Kamar</span>
            </div>
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-slate-400 block">Kamar Kosong / Siap Huni</span>
                    <span class="text-3xl font-black text-emerald-600 mt-1 block">{{ $rooms->sum('available_rooms') }} Kamar</span>
                </div>
                <button @click="showAddModal = true" class="h-11 w-11 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center shadow-md shadow-blue-500/25 transition" title="Tambah Kamar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>
        </div>

        {{-- Room List Table/Grid --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftar Tipe Kamar</h2>
                    <p class="text-xs text-slate-500">Kamar yang aktif ditawarkan kepada pencari kos.</p>
                </div>
                <x-seven-button @click="showAddModal = true" variant="primary" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Tipe Kamar
                </x-seven-button>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($rooms as $room)
                    <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/60 transition">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-extrabold text-sm sm:text-base text-slate-900">{{ $room->name }}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">
                                    {{ $room->room_size ?: 'Ukuran Standar' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500">
                                Total Kapasitas: <strong>{{ $room->total_rooms }} kamar</strong> &bull; Tersedia siap huni: <strong class="text-emerald-600">{{ $room->available_rooms }} kamar</strong>
                            </p>
                        </div>

                        <div class="flex items-center gap-6 sm:self-center">
                            <div class="text-right">
                                <span class="text-base sm:text-lg font-black text-blue-600 block">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-slate-400">per bulan</span>
                            </div>

                            <form action="{{ route('owner.rooms.destroy', [$kost, $room]) }}" method="POST" onsubmit="return confirm('Hapus tipe kamar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus Kamar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-xs text-slate-400">
                        Belum ada kamar yang didaftarkan. Klik tombol "Tambah Tipe Kamar" di atas.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Modal Tambah Kamar Baru --}}
        <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
            <div @click.outside="showAddModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5 text-slate-800">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-extrabold text-base text-slate-900">Tambah Tipe Kamar Baru</h3>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('owner.rooms.store', $kost) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Nama Kamar <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: Kamar VIP King, Tipe B" required class="w-full text-xs rounded-xl border-slate-200">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Tarif Sewa / Bulan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="1200000" min="100000" step="50000" required class="w-full text-xs rounded-xl border-slate-200 font-semibold">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase">Total Unit <span class="text-red-500">*</span></label>
                            <input type="number" name="total_rooms" value="4" min="1" required class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase">Unit Kosong <span class="text-red-500">*</span></label>
                            <input type="number" name="available_rooms" value="2" min="0" required class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Ukuran Kamar (Opsional)</label>
                        <input type="text" name="room_size" placeholder="Contoh: 3.5 x 4 meter" class="w-full text-xs rounded-xl border-slate-200">
                    </div>

                    <div class="pt-3 flex gap-2">
                        <button type="button" @click="showAddModal = false" class="flex-1 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                            Simpan Kamar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-seven-layout>
