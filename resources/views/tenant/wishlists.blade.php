<x-tenant-layout title="Kos Favorit Saya">

    <div class="space-y-6 sm:space-y-8">

        {{-- Header Card (White text judul, tanpa text helper) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Kos Favorit Saya
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('search') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-900 text-xs font-bold shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Cari Kos Lainnya</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-emerald-800 flex items-center gap-3 text-xs sm:text-sm font-semibold shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($kosts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kosts as $kost)
                    <div class="relative flex flex-col justify-between group">
                        <x-kost-card 
                            :title="$kost->name"
                            :slug="$kost->slug"
                            :type="$kost->type"
                            :city="$kost->regency->name ?? ''"
                            :province="$kost->province->name ?? ''"
                            :price="$kost->price_start"
                            :rentType="$kost->rent_type"
                            :image="$kost->primaryImage?->file_path ? asset('storage/' . $kost->primaryImage->file_path) : null"
                            :isVerified="$kost->verification_status === 'verified'"
                            :isFeatured="(bool) $kost->is_featured"
                            :availableRooms="$kost->rooms->sum('available_rooms')"
                            :facilities="$kost->facilities->pluck('name')->toArray()"
                        />

                        {{-- Tombol Hapus dari Favorit --}}
                        <div class="mt-2 text-right">
                            <form method="POST" action="{{ route('tenant.wishlists.toggle', $kost->id) }}" onsubmit="return confirm('Hapus kos ini dari daftar favorit?');">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-rose-600 font-semibold p-1 transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus dari Favorit</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $kosts->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto shadow-xs space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900">Belum Ada Kos di Daftar Favorit</h3>
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('search') }}" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition shadow-xs">
                        Cari Kos Sekarang
                    </a>
                    <a href="{{ route('map') }}" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Buka Peta Kos
                    </a>
                </div>
            </div>
        @endif

    </div>

</x-tenant-layout>
