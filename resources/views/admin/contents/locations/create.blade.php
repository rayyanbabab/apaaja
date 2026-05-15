@extends('admin.layouts.dashboard')

@section('content')
<style>
html.dark .loc-card { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .loc-card-header { background-color: rgba(15,23,42,0.6) !important; border-color: #334155 !important; }
html.dark .loc-card-footer { background-color: #0f172a !important; border-color: #334155 !important; }
html.dark .loc-label { color: #94a3b8 !important; }
html.dark .loc-label-hint { color: #64748b !important; }
html.dark .loc-btn-back,
html.dark .loc-btn-cancel { background-color: #1e293b !important; border-color: #334155 !important; color: #94a3b8 !important; }
html.dark .loc-btn-back:hover,
html.dark .loc-btn-cancel:hover { background-color: #334155 !important; color: #e2e8f0 !important; }
html.dark .loc-radio-label { border-color: #334155 !important; }
html.dark .loc-radio-label:hover { background-color: rgba(255,255,255,0.05) !important; }
html.dark .loc-radio-label span { color: #94a3b8 !important; }
html.dark .loc-radio-label:has(:checked) span { color: #e2e8f0 !important; }
html.dark .loc-radio-aktif:has(:checked) { border-color: #34d399 !important; background-color: rgba(16,185,129,0.15) !important; }
html.dark .loc-radio-nonaktif:has(:checked) { border-color: #f87171 !important; background-color: rgba(239,68,68,0.15) !important; }
html.dark .loc-auto-btn { background-color: rgba(59,130,246,0.2) !important; color: #60a5fa !important; }
html.dark .loc-auto-btn:hover { background-color: rgba(59,130,246,0.3) !important; }
html.dark .loc-breadcrumb { color: #64748b !important; }
html.dark .loc-breadcrumb a { color: #64748b !important; }
html.dark .loc-breadcrumb a:hover { color: #60a5fa !important; }
html.dark .loc-breadcrumb span { color: #94a3b8 !important; }
html.dark .loc-title { color: #f1f5f9 !important; }
html.dark .loc-subtitle { color: #64748b !important; }
html.dark .loc-icon-wrap { background-color: rgba(59,130,246,0.2) !important; }
html.dark .loc-card-header-title { color: #e2e8f0 !important; }
html.dark .loc-card-header-sub { color: #64748b !important; }
</style>
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="loc-breadcrumb flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="{{ route($routePrefix . '.locations.index') }}" class="hover:text-blue-600 transition-colors">Manajemen Lokasi</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-600 font-medium">Tambah Lokasi</span>
            </div>
            <h1 class="loc-title text-xl font-bold text-gray-900">Tambah Lokasi Baru</h1>
            <p class="loc-subtitle text-sm text-gray-400 mt-0.5">Tambahkan rak, lemari, atau ruangan penyimpanan baru</p>
        </div>
        <a href="{{ route($routePrefix . '.locations.index') }}"
           class="loc-btn-back inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-red-800 mb-1">Terdapat kesalahan:</h3>
                    <ul class="text-sm text-red-700 space-y-0.5 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <div class="loc-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Form Header --}}
        <div class="loc-card-header px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
            <div class="loc-icon-wrap w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="loc-card-header-title text-sm font-semibold text-gray-900">Informasi Lokasi</p>
                <p class="loc-card-header-sub text-xs text-gray-400">Isi detail informasi lokasi penyimpanan</p>
            </div>
        </div>

        <form method="POST" action="{{ route($routePrefix . '.locations.store') }}">
            @csrf

            <div class="px-6 py-5 space-y-5">

                {{-- Nama --}}
                <div class="space-y-1.5">
                    <label for="name" class="loc-label block text-sm font-semibold text-gray-700">
                        Nama Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="cth: Lemari Server, Rak A, Gudang Utama"
                           oninput="autoKode(this.value)"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-300 bg-red-50 @enderror">
                    @error('name')
                        <p class="text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kode --}}
                <div class="space-y-1.5">
                    <label for="kode" class="loc-label block text-sm font-semibold text-gray-700">
                        Kode Singkat
                        <span class="loc-label-hint text-xs font-normal text-gray-400">(opsional — auto-generate dari nama)</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="kode" name="kode" value="{{ old('kode') }}"
                               placeholder="cth: LSV"
                               maxlength="20"
                               oninput="kodeManuallyEdited = true"
                               class="w-full px-3 py-2.5 pr-20 text-sm font-mono border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kode') border-red-300 bg-red-50 @enderror">
                        <button type="button" onclick="resetKodeAuto()"
                                class="loc-auto-btn absolute right-2 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded-md transition-colors">
                            ↺ Auto
                        </button>
                    </div>
                    @error('kode')
                        <p class="text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Parent --}}
                <div class="space-y-1.5">
                    <label for="parent_id" class="loc-label block text-sm font-semibold text-gray-700">
                        Lokasi Induk
                        <span class="loc-label-hint text-xs font-normal text-gray-400">(opsional — untuk sub-lokasi)</span>
                    </label>
                    <select id="parent_id" name="parent_id"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="">— Lokasi Utama (tanpa induk) —</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}{{ $parent->kode ? ' [' . $parent->kode . ']' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-1.5">
                    <label for="deskripsi" class="loc-label block text-sm font-semibold text-gray-700">
                        Deskripsi
                        <span class="loc-label-hint text-xs font-normal text-gray-400">(opsional)</span>
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                              placeholder="Keterangan tambahan tentang lokasi ini..."
                              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Status --}}
                <div class="space-y-2">
                    <label class="loc-label block text-sm font-semibold text-gray-700">Status <span class="text-red-500">*</span></label>
                    <div class="flex gap-3">
                        <label class="loc-radio-label loc-radio-aktif flex items-center gap-2.5 px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="status" value="active" {{ old('status', 'active') === 'active' ? 'checked' : '' }}
                                   class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                            <span class="text-sm font-medium text-gray-700">Aktif</span>
                        </label>
                        <label class="loc-radio-label loc-radio-nonaktif flex items-center gap-2.5 px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-red-400 has-[:checked]:bg-red-50">
                            <input type="radio" name="status" value="inactive" {{ old('status') === 'inactive' ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-500 border-gray-300 focus:ring-red-500">
                            <span class="text-sm font-medium text-gray-700">Nonaktif</span>
                        </label>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="loc-card-footer flex items-center justify-between px-6 py-4 bg-gray-50 border-t border-gray-100">
                <a href="{{ route($routePrefix . '.locations.index') }}"
                   class="loc-btn-cancel inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Simpan Lokasi
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    let kodeManuallyEdited = {{ old('kode') ? 'true' : 'false' }};

    function generateKode(nama) {
        if (!nama || !nama.trim()) return '';
        const words = nama.trim().split(/\s+/).filter(w => w.length > 0);
        if (words.length === 1) {
            const w = words[0];
            const digits = w.match(/\d+$/) ? w.match(/\d+$/)[0] : '';
            const letters = w.replace(/\d+$/, '');
            const vowels = /^[aeiouAEIOU]$/;
            let picked = '';
            for (let i = 0; i < letters.length && picked.length < 3; i++) {
                if (i === 0 || !vowels.test(letters[i])) picked += letters[i];
            }
            for (let i = 1; i < letters.length && picked.length < 3; i++) {
                if (!picked.includes(letters[i])) picked += letters[i];
            }
            return (picked + digits).toUpperCase().substring(0, 6);
        }
        let code = '';
        for (let i = 0; i < words.length; i++) {
            const w = words[i];
            if (i < words.length - 1) {
                code += w[0];
            } else {
                const digits = w.match(/\d+$/) ? w.match(/\d+$/)[0] : '';
                code += w[0] + digits;
            }
        }
        return code.toUpperCase().substring(0, 6);
    }

    function autoKode(val) {
        if (kodeManuallyEdited) return;
        document.getElementById('kode').value = generateKode(val);
    }

    function resetKodeAuto() {
        kodeManuallyEdited = false;
        const nama = document.getElementById('name').value;
        document.getElementById('kode').value = generateKode(nama);
        document.getElementById('kode').focus();
    }
</script>
@endsection
