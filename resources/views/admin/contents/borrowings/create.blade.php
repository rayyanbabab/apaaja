@extends('admin.layouts.dashboard')
@section('content')
<style>
/* Searchable Select Component */
.search-select-wrapper { position: relative; }
.search-select-input {
    width: 100%; padding: 8px 36px 8px 12px; border: 1px solid #D1D5DB;
    border-radius: 8px; font-size: 14px; background: #fff;
    cursor: pointer; outline: none; box-sizing: border-box;
    transition: border-color .15s, box-shadow .15s;
}
.search-select-input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
.search-select-input.error { border-color: #EF4444; }
.search-select-arrow {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    pointer-events: none; color: #6B7280;
}
.search-select-dropdown {
    position: absolute; z-index: 999; left: 0; right: 0; top: calc(100% + 4px);
    background: #fff; border: 1px solid #E5E7EB; border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.12); display: none; overflow: hidden;
}
.search-select-dropdown.open { display: block; }
.search-select-search {
    padding: 10px 12px; border-bottom: 1px solid #F3F4F6; position: sticky; top: 0; background: #fff;
}
.search-select-search input {
    width: 100%; border: 1px solid #E5E7EB; border-radius: 6px; padding: 6px 10px;
    font-size: 13px; outline: none; box-sizing: border-box;
}
.search-select-search input:focus { border-color: #3B82F6; }
.search-select-list { max-height: 220px; overflow-y: auto; }
.search-select-option {
    padding: 9px 14px; cursor: pointer; font-size: 13px; color: #374151;
    display: flex; flex-direction: column; gap: 2px;
}
.search-select-option:hover, .search-select-option.focused { background: #EFF6FF; }
.search-select-option.selected { background: #DBEAFE; color: #1D4ED8; font-weight: 600; }
.search-select-option .opt-main { font-weight: 500; }
.search-select-option .opt-sub { font-size: 11px; color: #9CA3AF; }
.search-select-option.selected .opt-sub { color: #93C5FD; }
.search-select-empty { padding: 20px; text-align: center; font-size: 13px; color: #9CA3AF; }
</style>

<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Peminjaman Barang</h1>
                    <p class="text-sm text-gray-600 mt-1">Catat peminjaman barang baru</p>
                </div>
                <a href="{{ route($routePrefix . '.borrowings.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:14px 18px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
            <svg style="width:18px;height:18px;color:#DC2626;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span style="font-size:13px;font-weight:600;color:#991B1B;">Harap perbaiki kesalahan berikut:</span>
        </div>
        <ul style="margin:0;padding-left:20px;">
            @foreach($errors->all() as $error)
                <li style="font-size:13px;color:#B91C1C;margin-bottom:4px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Form Peminjaman</h3>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route($routePrefix . '.borrowings.store') }}" method="POST" class="space-y-6" id="borrowingForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Barang (Searchable + Scan) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="item_id" id="item_id" value="{{ old('item_id') }}">
                        <div class="flex gap-2 items-start">
                            <div class="search-select-wrapper flex-1" id="itemSelectWrapper">
                                <input type="text" class="search-select-input @error('item_id') error @enderror"
                                       id="itemDisplay" placeholder="Pilih Barang" readonly autocomplete="off">
                                <svg class="search-select-arrow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <div class="search-select-dropdown" id="itemDropdown">
                                    <div class="search-select-search">
                                        <input type="text" id="itemSearch" placeholder="🔍  Cari nama barang atau supplier...">
                                    </div>
                                    <div class="search-select-list" id="itemList">
                                        <div class="search-select-option" data-value="" data-stock="" data-code="" data-supplier="" data-category="" data-desc="">
                                            <span class="opt-main text-gray-400">— Pilih Barang —</span>
                                        </div>
                                        @foreach($items as $item)
                                        <div class="search-select-option"
                                             data-value="{{ $item->id }}"
                                             data-stock="{{ $item->stok_peminjaman }}"
                                             data-code="{{ 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) }}"
                                             data-supplier="{{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }}"
                                             data-category="{{ $item->category->name ?? 'N/A' }}"
                                             data-desc="{{ $item->keterangan ?? 'Tidak ada deskripsi' }}"
                                             data-location-label="{{ $item->location_label ?? '' }}"
                                             data-location-kode="{{ $item->location_kode ?? '' }}"
                                             data-label="{{ $item->nama }} — {{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }} (Stok: {{ $item->stok_peminjaman }})">
                                            <span class="opt-main">{{ $item->nama }}</span>
                                            <span class="opt-sub">{{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }} &middot; Stok: {{ $item->stok_peminjaman }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {{-- Scan Button --}}
                            <button type="button" onclick="openItemScan(null, 'peminjaman')" title="Scan Barcode Barang Peminjaman"
                                class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 bg-violet-600 text-white text-xs font-semibold rounded-lg hover:bg-violet-700 transition-colors shadow-sm"
                                style="height:38px;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                Scan
                            </button>
                        </div>
                        @error('item_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="stock-info" class="mt-1 text-sm text-gray-500 hidden">
                            Stok tersedia: <span id="available-stock" class="font-semibold text-green-600">0</span>
                        </div>
                    </div>

                    {{-- ── PEMINJAM (Searchable + QR Scan) ── --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Peminjam <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id') }}">
                        <div class="flex gap-2 items-start">
                            <div class="search-select-wrapper flex-1" id="userSelectWrapper">
                                <input type="text" class="search-select-input @error('user_id') error @enderror"
                                       id="userDisplay" placeholder="Pilih Peminjam" readonly
                                       autocomplete="off">
                                <svg class="search-select-arrow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <div class="search-select-dropdown" id="userDropdown">
                                    <div class="search-select-search">
                                        <input type="text" id="userSearch" placeholder="🔍  Cari nama atau email peminjam...">
                                    </div>
                                    <div class="search-select-list" id="userList">
                                        <div class="search-select-option" data-value="" data-label="">
                                            <span class="opt-main text-gray-400">— Pilih Peminjam —</span>
                                        </div>
                                        @foreach($users as $user)
                                        <div class="search-select-option"
                                             data-value="{{ $user->id }}"
                                             data-label="{{ $user->name }} — {{ $user->email }}">
                                            <span class="opt-main">{{ $user->name }}</span>
                                            <span class="opt-sub">{{ $user->email }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {{-- QR Scan Button --}}
                            <button type="button" onclick="openQrScanner('borrowing')"
                                title="Scan QR Code Peminjam"
                                class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-150 shadow-sm"
                                style="height:38px;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                Scan QR
                            </button>
                        </div>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah', 1) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('jumlah') border-red-300 @enderror">
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Pinjam --}}
                    <div>
                        <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                               min="{{ now()->format('Y-m-d') }}"
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tanggal_pinjam') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500">
                            Tanggal pinjam tidak boleh sebelum hari ini.
                        </p>
                        @error('tanggal_pinjam')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Rencana Kembali --}}
                    <div>
                        <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Rencana Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana"
                               data-max-borrow-days="{{ $maxBorrowDays ?? 7 }}"
                               value="{{ old('tanggal_kembali_rencana') }}" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tanggal_kembali_rencana') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500">
                            Maksimal {{ $maxBorrowDays ?? 7 }} hari dari tanggal pinjam.
                        </p>
                        @error('tanggal_kembali_rencana')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kondisi Saat Dipinjam --}}
                    <div>
                        <label for="kondisi_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Saat Dipinjam</label>
                        <textarea name="kondisi_pinjam" id="kondisi_pinjam" rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('kondisi_pinjam') border-red-300 @enderror"
                                  placeholder="Contoh: Baik, tidak ada kerusakan">{{ old('kondisi_pinjam') }}</textarea>
                        @error('kondisi_pinjam')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Item Detail Card --}}
                <div id="itemDetails" class="hidden bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-purple-900 mb-3">Detail Barang</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div><span class="text-purple-600 font-medium">Kode Barang:</span><span id="itemCode" class="ml-2 font-semibold text-purple-900"></span></div>
                        <div><span class="text-purple-600 font-medium">Supplier:</span><span id="supplierName" class="ml-2 font-semibold text-purple-900"></span></div>
                        <div><span class="text-purple-600 font-medium">Kategori:</span><span id="categoryName" class="ml-2 font-semibold text-purple-900"></span></div>
                        <div><span class="text-purple-600 font-medium">Stok Tersedia:</span><span id="availableStockDetail" class="ml-2 font-semibold text-green-600"></span></div>
                        <div>
                            <span class="text-purple-600 font-medium">Tipe Barang:</span>
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">PEMINJAMAN</span>
                        </div>
                    </div>
                    {{-- Location row --}}
                    <div id="itemLocationRow" class="hidden mt-3 pt-3 border-t border-purple-200">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-xs font-semibold text-indigo-500">Lokasi Barang:</span>
                            <span id="itemLocationLabel" class="text-xs font-bold text-indigo-700"></span>
                            <span id="itemLocationKode" class="text-[10px] font-bold font-mono bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded-md"></span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-purple-200">
                        <span class="text-purple-600 font-medium">Deskripsi:</span>
                        <p id="itemDescription" class="mt-1 text-sm text-purple-800 italic"></p>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('keterangan') border-red-300 @enderror"
                              placeholder="Catatan tambahan tentang peminjaman">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route($routePrefix . '.borrowings.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                            style="background:#2563EB;color:#fff;border:none;cursor:pointer;"
                            class="inline-flex items-center px-5 py-2 rounded-md shadow-sm text-sm font-semibold gap-2"
                            onmouseover="this.style.background='#1D4ED8'" onmouseout="this.style.background='#2563EB'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
/* =============================================
   Generic Searchable Select Builder
   ============================================= */
function buildSearchSelect({ wrapperId, dropdownId, listId, searchId, displayId, hiddenId, onSelect }) {
    const wrapper  = document.getElementById(wrapperId);
    const dropdown = document.getElementById(dropdownId);
    const list     = document.getElementById(listId);
    const search   = document.getElementById(searchId);
    const display  = document.getElementById(displayId);
    const hidden   = document.getElementById(hiddenId);
    const allOpts  = Array.from(list.querySelectorAll('.search-select-option'));

    function openDropdown() {
        dropdown.classList.add('open');
        search.value = '';
        filterOptions('');
        search.focus();
    }

    function closeDropdown() {
        dropdown.classList.remove('open');
    }

    function filterOptions(q) {
        const term = q.toLowerCase();
        let hasVisible = false;
        allOpts.forEach(opt => {
            const text = (opt.dataset.label || opt.textContent).toLowerCase();
            const show = !term || text.includes(term);
            opt.style.display = show ? '' : 'none';
            if (show) hasVisible = true;
        });
        // Empty state
        let emptyEl = list.querySelector('.search-select-empty');
        if (!hasVisible) {
            if (!emptyEl) {
                emptyEl = document.createElement('div');
                emptyEl.className = 'search-select-empty';
                emptyEl.textContent = 'Tidak ada hasil ditemukan';
                list.appendChild(emptyEl);
            }
            emptyEl.style.display = '';
        } else if (emptyEl) {
            emptyEl.style.display = 'none';
        }
    }

    function selectOption(opt) {
        const val   = opt.dataset.value || '';
        const label = opt.dataset.label || opt.querySelector('.opt-main')?.textContent || '';
        hidden.value   = val;
        display.value  = val ? label : '';
        display.placeholder = val ? '' : display.dataset.placeholder;

        allOpts.forEach(o => o.classList.remove('selected'));
        if (val) opt.classList.add('selected');

        closeDropdown();
        if (onSelect) onSelect(opt);
    }

    // Store placeholder
    display.dataset.placeholder = display.placeholder;

    // Toggle dropdown
    display.addEventListener('click', () => dropdown.classList.contains('open') ? closeDropdown() : openDropdown());

    // Search filter
    search.addEventListener('input', () => filterOptions(search.value));

    // Option click
    list.addEventListener('click', e => {
        const opt = e.target.closest('.search-select-option');
        if (opt) selectOption(opt);
    });

    // Close on outside click
    document.addEventListener('click', e => {
        if (!wrapper.contains(e.target)) closeDropdown();
    });

    // Keyboard navigation
    search.addEventListener('keydown', e => {
        const visible = allOpts.filter(o => o.style.display !== 'none');
        let idx = visible.findIndex(o => o.classList.contains('focused'));
        if (e.key === 'ArrowDown') { e.preventDefault(); idx = Math.min(idx + 1, visible.length - 1); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); idx = Math.max(idx - 1, 0); }
        else if (e.key === 'Enter' && idx >= 0) { e.preventDefault(); selectOption(visible[idx]); return; }
        else if (e.key === 'Escape') { closeDropdown(); return; }
        allOpts.forEach(o => o.classList.remove('focused'));
        if (visible[idx]) { visible[idx].classList.add('focused'); visible[idx].scrollIntoView({ block: 'nearest' }); }
    });

    // Pre-select old value
    const preVal = hidden.value;
    if (preVal) {
        const preOpt = allOpts.find(o => o.dataset.value == preVal);
        if (preOpt) selectOption(preOpt);
    }
}

/* =============================================
   Item Details Panel
   ============================================= */
function handleItemSelect(opt) {
    const stock      = opt.dataset.stock;
    const stockInfo  = document.getElementById('stock-info');
    const stockSpan  = document.getElementById('available-stock');
    const details    = document.getElementById('itemDetails');
    const qty        = document.getElementById('jumlah');

    if (opt.dataset.value) {
        stockInfo.classList.remove('hidden');
        stockSpan.textContent = stock;
        qty.setAttribute('max', stock);

        details.classList.remove('hidden');
        document.getElementById('itemCode').textContent             = opt.dataset.code || 'N/A';
        document.getElementById('supplierName').textContent         = opt.dataset.supplier || 'N/A';
        document.getElementById('categoryName').textContent         = opt.dataset.category || 'N/A';
        document.getElementById('availableStockDetail').textContent = stock + ' unit';
        document.getElementById('itemDescription').textContent      = opt.dataset.desc || 'Tidak ada deskripsi';

        // Location badge
        const locRow   = document.getElementById('itemLocationRow');
        const locLabel = opt.dataset.locationLabel || '';
        const locKode  = opt.dataset.locationKode  || '';
        if (locLabel) {
            document.getElementById('itemLocationLabel').textContent = locLabel;
            const kodeEl = document.getElementById('itemLocationKode');
            kodeEl.textContent  = locKode;
            kodeEl.style.display = locKode ? '' : 'none';
            locRow.classList.remove('hidden');
        } else {
            locRow.classList.add('hidden');
        }
    } else {
        stockInfo.classList.add('hidden');
        details.classList.add('hidden');
        qty.removeAttribute('max');
    }
}

/* =============================================
   Date validation
   ============================================= */
function handleDateValidation() {
    const borrow = document.getElementById('tanggal_pinjam');
    const ret    = document.getElementById('tanggal_kembali_rencana');
    if (!borrow.value) return;
    const maxBorrowDays = parseInt(ret.dataset.maxBorrowDays || '7', 10);

    const minDate = new Date(borrow.value);
    minDate.setDate(minDate.getDate() + 1);
    const min = minDate.toISOString().split('T')[0];
    ret.setAttribute('min', min);

    const maxDate = new Date(borrow.value);
    maxDate.setDate(maxDate.getDate() + maxBorrowDays);
    const max = maxDate.toISOString().split('T')[0];
    ret.setAttribute('max', max);

    if (ret.value && ret.value <= borrow.value) ret.value = min;
    if (ret.value && ret.value > max) ret.value = max;
}

/* =============================================
   Init
   ============================================= */
document.addEventListener('DOMContentLoaded', function () {
    buildSearchSelect({
        wrapperId:  'itemSelectWrapper',
        dropdownId: 'itemDropdown',
        listId:     'itemList',
        searchId:   'itemSearch',
        displayId:  'itemDisplay',
        hiddenId:   'item_id',
        onSelect:   handleItemSelect,
    });

    buildSearchSelect({
        wrapperId:  'userSelectWrapper',
        dropdownId: 'userDropdown',
        listId:     'userList',
        searchId:   'userSearch',
        displayId:  'userDisplay',
        hiddenId:   'user_id',
        onSelect:   null,
    });

    document.getElementById('tanggal_pinjam').addEventListener('change', handleDateValidation);
    handleDateValidation();

    // Validate before submit
    document.getElementById('borrowingForm').addEventListener('submit', function(e) {
        const itemVal = document.getElementById('item_id').value;
        const userVal = document.getElementById('user_id').value;
        if (!itemVal) {
            e.preventDefault();
            document.getElementById('itemDisplay').style.borderColor = '#EF4444';
            document.getElementById('itemDisplay').focus();
            alert('Silakan pilih barang terlebih dahulu.');
            return;
        }
        if (!userVal) {
            e.preventDefault();
            document.getElementById('userDisplay').style.borderColor = '#EF4444';
            document.getElementById('userDisplay').focus();
            alert('Silakan pilih peminjam terlebih dahulu.');
            return;
        }
    });
});
</script>

{{-- ═══════════════════════════════════════════
     QR SCANNER MODAL
═══════════════════════════════════════════ --}}
@include('admin.components.partials.qr-scanner-modal')

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
/* Users data map: id → {name, email} */
var qrUsersMap = {};
@foreach($users as $u)
qrUsersMap[{{ $u->id }}] = { name: @json($u->name), email: @json($u->email) };
@endforeach

function selectBorrowingUser(userId) {
    var allOpts = document.querySelectorAll('#userList .search-select-option');
    var found = null;
    allOpts.forEach(function(opt) {
        if (String(opt.dataset.value) === String(userId)) found = opt;
    });
    if (found) {
        found.click();
        return true;
    }
    return false;
}

window.onQrUserFound = function(userData) {
    var ok = selectBorrowingUser(userData.id);
    if (!ok) {
        showQrError('User dengan ID ' + userData.id + ' tidak ditemukan di sistem.');
    }
};
</script>
@endpush
@endsection

{{-- Item Scan Modal (hanya tipe peminjaman) --}}
@include('admin.components.partials.item-scan-modal')

<script>
/**
 * Callback dipanggil oleh item-scan-modal setelah barcode ditemukan & lolos filter tipe.
 * Untuk borrowings: tidak ada cnt (null), langsung trigger opsi di #itemList.
 */
window._isSelectCallback = function(item, cnt) {
    var allOpts = document.querySelectorAll('#itemList .search-select-option');
    var found   = null;
    allOpts.forEach(function(opt) {
        if (String(opt.dataset.value) === String(item.id)) found = opt;
    });
    if (found) {
        found.click(); // triggers handleItemSelect via buildSearchSelect
    } else {
        // Fallback: inject & click a new option
        var el = document.createElement('div');
        el.className = 'search-select-option';
        el.dataset.value         = item.id;
        el.dataset.stock         = item.stok_peminjaman;
        el.dataset.code          = item.kode;
        el.dataset.supplier      = item.supplier || '';
        el.dataset.category      = item.category || '';
        el.dataset.desc          = item.keterangan || '';
        el.dataset.locationLabel = item.location || '';
        el.dataset.locationKode  = '';
        el.dataset.label         = item.nama;
        el.innerHTML             = '<span class="opt-main">' + item.nama + '</span>';
        document.getElementById('itemList').appendChild(el);
        el.click();
    }
};
</script>
