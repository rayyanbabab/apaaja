@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.procurement-requests.index') }}" class="hover:text-orange-500 transition-colors">Permintaan Pengadaan</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-600 font-medium">#{{ $procurement->id }}</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Detail Permintaan Pengadaan</h1>
        </div>
        <a href="{{ route('admin.procurement-requests.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    {{-- Request Info Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-1.5
            @if($procurement->status === 'pending')  bg-gradient-to-r from-yellow-400 to-amber-400
            @elseif($procurement->status === 'approved') bg-gradient-to-r from-emerald-400 to-green-400
            @else bg-gradient-to-r from-red-400 to-rose-400
            @endif">
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between gap-4 mb-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Permintaan #{{ $procurement->id }}</p>
                        <h2 class="text-lg font-bold text-gray-900">{{ $procurement->nama_barang }}</h2>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $procurement->jumlah }} unit • Diajukan {{ $procurement->created_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</p>
                    </div>
                </div>
                @if($procurement->status === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-yellow-100 text-yellow-800 border border-yellow-200 flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>Pending
                    </span>
                @elseif($procurement->status === 'approved')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-emerald-100 text-emerald-800 border border-emerald-200 flex-shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Disetujui
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-red-100 text-red-700 border border-red-200 flex-shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Ditolak
                    </span>
                @endif
            </div>

            {{-- Info grid --}}
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Pemohon</p>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center text-xs font-bold text-blue-700">
                            {{ strtoupper(substr($procurement->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $procurement->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $procurement->user->email }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Jumlah Diminta</p>
                    <p class="text-xl font-bold text-gray-900">{{ $procurement->jumlah }} <span class="text-sm font-normal text-gray-400">unit</span></p>
                </div>
            </div>

            @if($procurement->alasan)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Alasan / Keperluan</p>
                    <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 rounded-xl px-4 py-3">{{ $procurement->alasan }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- If already reviewed --}}
    @if($procurement->status !== 'pending')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Keputusan Admin</p>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold text-gray-600">
                    {{ strtoupper(substr($procurement->reviewer?->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">{{ $procurement->reviewer?->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400 mb-2">{{ $procurement->reviewed_at?->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</p>
                    @if($procurement->admin_notes)
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $procurement->admin_notes }}</p>
                    @endif

                    @if($procurement->status === 'approved' && $procurement->item)
                        <div class="mt-3 flex items-center gap-3 bg-emerald-50 border border-emerald-100 rounded-xl px-3 py-2.5">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <div>
                                <p class="text-xs font-semibold text-emerald-800">Barang: {{ $procurement->item->nama }}</p>
                                <p class="text-xs text-emerald-600">Stok bertambah {{ $procurement->jumlah }} unit</p>
                            </div>
                            <a href="{{ route('admin.inventory.show', $procurement->item->id) }}"
                               class="ml-auto text-xs text-emerald-700 underline hover:text-emerald-900">Lihat</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Action Forms (only if pending) --}}
    @if($procurement->status === 'pending')

        {{-- APPROVE FORM --}}
        <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm overflow-hidden" id="approveForm">
            <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/60 flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Setujui Permintaan</p>
                    <p class="text-xs text-gray-500">Pilih item existing atau buat item baru, lalu stok akan otomatis bertambah</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.procurement-requests.approve', $procurement->id) }}" class="px-6 py-5 space-y-4">
                @csrf

                {{-- Action Type Toggle --}}
                <div x-data="{ actionType: '{{ old('action_type', 'existing') }}' }" class="space-y-4">

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Pilih Jenis Tindakan <span class="text-red-500">*</span></label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2.5 px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50 flex-1">
                                <input type="radio" name="action_type" value="existing"
                                       x-model="actionType"
                                       {{ old('action_type', 'existing') === 'existing' ? 'checked' : '' }}
                                       class="text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Item yang Sudah Ada</span>
                                    <p class="text-xs text-gray-400">Tambah stok item existing</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-2.5 px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 flex-1">
                                <input type="radio" name="action_type" value="new"
                                       x-model="actionType"
                                       {{ old('action_type') === 'new' ? 'checked' : '' }}
                                       class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Buat Item Baru</span>
                                    <p class="text-xs text-gray-400">Tambahkan ke inventaris baru</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Existing item dropdown --}}
                    <div x-show="actionType === 'existing'" x-transition class="space-y-1.5">
                        <label for="item_id" class="block text-sm font-semibold text-gray-700">Pilih Item Inventaris <span class="text-red-500">*</span></label>
                        <select name="item_id" id="item_id"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent bg-white @error('item_id') border-red-300 @enderror">
                            <option value="">— Pilih Item —</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                    [{{ $item->kode ?? 'no-kode' }}]
                                    — Stok: {{ $item->stok_reguler + $item->stok_peminjaman }}
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New item name --}}
                    <div x-show="actionType === 'new'" x-transition class="space-y-1.5">
                        <label for="new_item_name" class="block text-sm font-semibold text-gray-700">Nama Item Baru <span class="text-red-500">*</span></label>
                        <input type="text" name="new_item_name" id="new_item_name"
                               value="{{ old('new_item_name', $procurement->nama_barang) }}"
                               placeholder="Nama barang yang akan dibuat..."
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('new_item_name') border-red-300 @enderror">
                        <p class="text-xs text-gray-400">Item baru akan dibuat dengan kode otomatis. Anda bisa melengkapi detail lainnya di halaman Inventaris setelah ini.</p>
                        @error('new_item_name')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Admin Notes --}}
                <div class="space-y-1.5">
                    <label for="approve_notes" class="block text-sm font-semibold text-gray-700">
                        Catatan <span class="text-xs font-normal text-gray-400">(opsional)</span>
                    </label>
                    <textarea name="admin_notes" id="approve_notes" rows="2"
                              placeholder="Pesan untuk pemohon..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent resize-none">{{ old('admin_notes') }}</textarea>
                </div>

                <button type="submit"
                        onclick="return confirm('Yakin ingin menyetujui permintaan ini? Stok akan langsung bertambah.')"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 rounded-xl shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Setujui & Tambah Stok
                </button>
            </form>
        </div>

        {{-- REJECT FORM --}}
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden" id="rejectForm">
            <div class="px-6 py-4 border-b border-gray-100 bg-red-50/60 flex items-center gap-3">
                <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Tolak Permintaan</p>
                    <p class="text-xs text-gray-500">Berikan alasan penolakan kepada pemohon</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.procurement-requests.reject', $procurement->id) }}" class="px-6 py-5 space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label for="reject_notes" class="block text-sm font-semibold text-gray-700">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="admin_notes" id="reject_notes" rows="3" required
                              placeholder="Jelaskan mengapa permintaan ini tidak dapat disetujui..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent resize-none @error('admin_notes') border-red-300 @enderror">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        onclick="return confirm('Yakin ingin menolak permintaan ini?')"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 rounded-xl shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak Permintaan
                </button>
            </form>
        </div>

    @endif

</div>
@endsection
