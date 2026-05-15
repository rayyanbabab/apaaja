@extends('admin.layouts.dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Barang Keluar</h1>
                    <p class="text-sm text-gray-600 mt-1">Edit data barang keluar #{{ $outgoingItem->id }}</p>
                </div>
                <a href="{{ route($routePrefix . '.outgoing.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form id="update-form-{{ $outgoingItem->id }}" action="{{ route($routePrefix . '.outgoing.update', $outgoingItem->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Edit Informasi Barang Keluar</h3>
            </div>

            <div class="px-6 py-6 space-y-6">

                {{-- ============ Searchable: User ============ --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Diambil Oleh <span class="text-red-500">*</span>
                    </label>

                    @php $selUser = $users->firstWhere('id', old('user_id', $outgoingItem->user_id)); @endphp

                    <div class="ssd-wrapper" id="user-ssd-wrapper">
                        <input type="hidden" name="user_id" id="user_id_hidden"
                               value="{{ old('user_id', $outgoingItem->user_id) }}" required>
                        <button type="button" class="ssd-display" data-placeholder="-- Pilih User --">
                            <span class="ssd-display-text {{ $selUser ? '' : 'ssd-placeholder' }}">
                                {{ $selUser ? $selUser->name . ' — ' . $selUser->email : '-- Pilih User --' }}
                            </span>
                            <svg class="ssd-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="ssd-dropdown" id="user-ssd-dropdown">
                            <div class="ssd-search-wrap">
                                <input type="text" class="ssd-search" placeholder="🔍 Ketik nama atau email user..." autocomplete="off">
                            </div>
                            <div class="ssd-options">
                                <div class="ssd-option" data-value="">-- Pilih User --</div>
                                @foreach($users as $user)
                                    <div class="ssd-option {{ old('user_id', $outgoingItem->user_id) == $user->id ? 'selected' : '' }}"
                                         data-value="{{ $user->id }}"
                                         data-name="{{ $user->name }}"
                                         data-email="{{ $user->email }}">
                                        {{ $user->name }}
                                        <span style="color:#9ca3af;font-size:0.75rem"> · ID:{{ $user->id }} · {{ $user->email }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ============ Searchable: Item ============ --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>

                    @php $selItem = $items->firstWhere('id', old('item_id', $outgoingItem->item_id)); @endphp

                    <div class="ssd-wrapper" id="item-ssd-wrapper">
                        <input type="hidden" name="item_id" id="item_id_hidden"
                               value="{{ old('item_id', $outgoingItem->item_id) }}" required>
                        <button type="button" class="ssd-display" data-placeholder="-- Pilih Barang --">
                            <span class="ssd-display-text {{ $selItem ? '' : 'ssd-placeholder' }}">
                                {{ $selItem ? $selItem->nama . ' — ITM-' . str_pad($selItem->id, 4, '0', STR_PAD_LEFT) : '-- Pilih Barang --' }}
                            </span>
                            <svg class="ssd-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="ssd-dropdown" id="item-ssd-dropdown">
                            <div class="ssd-search-wrap">
                                <input type="text" class="ssd-search" placeholder="🔍 Ketik nama barang, kode, atau supplier..." autocomplete="off">
                            </div>
                            <div class="ssd-options">
                                <div class="ssd-option" data-value="">-- Pilih Barang --</div>
                                @foreach($items as $item)
                                    <div class="ssd-option {{ old('item_id', $outgoingItem->item_id) == $item->id ? 'selected' : '' }}"
                                         data-value="{{ $item->id }}"
                                         data-name="{{ $item->nama }}"
                                         data-stock="{{ $item->stok_total }}"
                                         data-code="ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}"
                                         data-supplier="{{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }}"
                                         data-category="{{ $item->category->name ?? 'N/A' }}"
                                         data-price="{{ $item->harga ?? 0 }}"
                                         data-description="{{ $item->keterangan ?? '' }}">
                                        {{ $item->nama }}
                                        <span style="color:#9ca3af;font-size:0.75rem">
                                            · ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                            · Stok: {{ $item->stok_total }}
                                            · {{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('item_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Item detail card --}}
                <div id="itemDetails" class="{{ $selItem ? '' : 'hidden' }} bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-3">Detail Barang</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div><span class="text-blue-600 font-medium">Kode Barang:</span> <span id="itemCode" class="ml-2 font-semibold text-blue-900">{{ $selItem ? 'ITM-' . str_pad($selItem->id, 4, '0', STR_PAD_LEFT) : '' }}</span></div>
                        <div><span class="text-blue-600 font-medium">Supplier:</span> <span id="supplierName" class="ml-2 font-semibold text-blue-900">{{ $selItem?->supplier->company_name ?? $selItem?->supplier->nama ?? '' }}</span></div>
                        <div><span class="text-blue-600 font-medium">Kategori:</span> <span id="categoryName" class="ml-2 font-semibold text-blue-900">{{ $selItem?->category->name ?? '' }}</span></div>
                        <div><span class="text-blue-600 font-medium">Stok Tersedia:</span> <span id="availableStock" class="ml-2 font-semibold text-green-600">{{ $selItem ? $selItem->stok_total . ' unit' : '' }}</span></div>
                        <div><span class="text-blue-600 font-medium">Harga Satuan:</span> <span id="itemPrice" class="ml-2 font-semibold text-green-600">{{ $selItem && $selItem->harga ? 'Rp ' . number_format($selItem->harga, 0, ',', '.') : 'Rp 0' }}</span></div>
                        <div><span class="text-blue-600 font-medium">Total Nilai:</span> <span id="totalValue" class="ml-2 font-semibold text-green-600">Rp 0</span></div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-blue-200">
                        <span class="text-blue-600 font-medium text-sm">Deskripsi:</span>
                        <p id="itemDescription" class="mt-1 text-sm text-blue-800 italic">{{ $selItem?->keterangan ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>

                {{-- Quantity --}}
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Keluar <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1" required
                           value="{{ old('jumlah', $outgoingItem->jumlah) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="Masukkan jumlah barang yang keluar">
                    @error('jumlah')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="stockWarning" class="mt-1 text-sm text-red-600 hidden">⚠ Jumlah melebihi stok yang tersedia!</p>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                              placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan', $outgoingItem->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route($routePrefix . '.outgoing.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="button" onclick="openModal('update-modal-{{ $outgoingItem->id }}')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Barang Keluar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.ssd-wrapper { position: relative; width: 100%; }
.ssd-display {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db; border-radius: 0.375rem;
    background: white; cursor: pointer; font-size: 0.875rem; color: #374151;
    transition: border-color .15s, box-shadow .15s; text-align: left;
}
.ssd-display:hover { border-color: #93c5fd; }
.ssd-display.open { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.2); }
.ssd-placeholder { color: #9ca3af; }
.ssd-arrow { transition: transform .2s; color: #6b7280; flex-shrink: 0; margin-left: 8px; }
.ssd-display.open .ssd-arrow { transform: rotate(180deg); }
.ssd-dropdown {
    display: none; flex-direction: column;
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: white; border: 1px solid #d1d5db; border-radius: .375rem;
    box-shadow: 0 10px 25px rgba(0,0,0,.12); z-index: 9999; max-height: 300px; overflow: hidden;
}
.ssd-dropdown.open { display: flex; }
.ssd-search-wrap { padding: 8px; border-bottom: 1px solid #f3f4f6; flex-shrink: 0; }
.ssd-search { width: 100%; padding: 7px 10px; border: 1px solid #d1d5db; border-radius: .375rem; font-size: .8rem; outline: none; }
.ssd-search:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); }
.ssd-options { overflow-y: auto; flex: 1; }
.ssd-option { padding: 9px 12px; font-size: .875rem; cursor: pointer; color: #374151; transition: background .1s; }
.ssd-option:hover { background: #eff6ff; color: #1d4ed8; }
.ssd-option.selected { background: #dbeafe; font-weight: 600; color: #1e40af; }
.ssd-option.no-result { color: #9ca3af; font-style: italic; cursor: default; }
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {

    const originalQuantity = {{ $outgoingItem->jumlah }};
    const originalItemId   = {{ $outgoingItem->item_id }};
    let currentStock = 0;
    let currentPrice = 0;

    /* ========= Generic SSD init ========= */
    function initSSD(wrapperId, onSelect) {
        const wrapper  = document.getElementById(wrapperId);
        const display  = wrapper.querySelector('.ssd-display');
        const dropdown = wrapper.querySelector('.ssd-dropdown');
        const search   = wrapper.querySelector('.ssd-search');
        const opts     = wrapper.querySelector('.ssd-options');
        const dispTxt  = display.querySelector('.ssd-display-text');
        const hidden   = wrapper.querySelector('input[type="hidden"]');

        display.addEventListener('click', () => {
            const isOpen = dropdown.classList.contains('open');
            // close all
            document.querySelectorAll('.ssd-dropdown.open').forEach(d => {
                d.classList.remove('open');
                d.closest('.ssd-wrapper').querySelector('.ssd-display').classList.remove('open');
            });
            if (!isOpen) {
                dropdown.classList.add('open'); display.classList.add('open');
                search.value = ''; filter(''); setTimeout(() => search.focus(), 30);
            }
        });

        function filter(q) {
            q = q.toLowerCase();
            let any = false;
            opts.querySelectorAll('.ssd-option:not(.no-result)').forEach(o => {
                if (!o.dataset.value) { o.style.display = ''; return; }
                const m = !q || o.textContent.toLowerCase().includes(q);
                o.style.display = m ? '' : 'none';
                if (m) any = true;
            });
            let nr = opts.querySelector('.no-result');
            if (!any && q) {
                if (!nr) { nr = document.createElement('div'); nr.className='ssd-option no-result'; nr.textContent='Tidak ditemukan'; opts.appendChild(nr); }
                nr.style.display = '';
            } else if (nr) nr.style.display = 'none';
        }

        search.addEventListener('input', e => filter(e.target.value));
        search.addEventListener('click', e => e.stopPropagation());

        opts.querySelectorAll('.ssd-option').forEach(opt => {
            opt.addEventListener('click', function() {
                const val = this.dataset.value || '';
                hidden.value = val;

                if (val) {
                    const tn = [...this.childNodes].find(n => n.nodeType === 3);
                    dispTxt.textContent = tn ? tn.textContent.trim() : this.textContent.trim();
                    dispTxt.classList.remove('ssd-placeholder');
                } else {
                    dispTxt.textContent = display.dataset.placeholder || '-- Pilih --';
                    dispTxt.classList.add('ssd-placeholder');
                }

                opts.querySelectorAll('.ssd-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                dropdown.classList.remove('open'); display.classList.remove('open');

                if (onSelect) onSelect(val, this.dataset);
            });
        });

        // init on load
        if (hidden.value) {
            const pre = opts.querySelector(`.ssd-option[data-value="${hidden.value}"]`);
            if (pre && onSelect) onSelect(hidden.value, pre.dataset);
        }
    }

    // Close on outside click
    document.addEventListener('click', e => {
        if (!e.target.closest('.ssd-wrapper')) {
            document.querySelectorAll('.ssd-dropdown.open').forEach(d => {
                d.classList.remove('open');
                d.closest('.ssd-wrapper').querySelector('.ssd-display').classList.remove('open');
            });
        }
    });

    /* ========= User SSD ========= */
    initSSD('user-ssd-wrapper', null); // no extra logic needed for user

    /* ========= Item SSD ========= */
    initSSD('item-ssd-wrapper', function(val, dataset) {
        const detailsDiv = document.getElementById('itemDetails');
        if (!val) {
            detailsDiv.classList.add('hidden');
            currentStock = 0; currentPrice = 0;
            calculateTotal(); return;
        }

        detailsDiv.classList.remove('hidden');

        document.getElementById('itemCode').textContent      = dataset.code || 'N/A';
        document.getElementById('supplierName').textContent  = dataset.supplier || 'N/A';
        document.getElementById('categoryName').textContent  = dataset.category || 'N/A';
        document.getElementById('itemDescription').textContent = dataset.description || 'Tidak ada deskripsi';

        // Available stock (add back original qty if same item)
        let stock = parseInt(dataset.stock) || 0;
        if (val == originalItemId) stock += originalQuantity;
        currentStock = stock;
        document.getElementById('availableStock').textContent = stock + ' unit';
        document.getElementById('jumlah').max = stock;

        currentPrice = parseFloat(dataset.price) || 0;
        document.getElementById('itemPrice').textContent = currentPrice > 0
            ? 'Rp ' + currentPrice.toLocaleString('id-ID') : 'Rp 0';

        calculateTotal();
        validateQty();
    });

    /* ========= Quantity ========= */
    document.getElementById('jumlah').addEventListener('input', () => {
        calculateTotal(); validateQty();
    });

    function calculateTotal() {
        const qty = parseInt(document.getElementById('jumlah').value) || 0;
        document.getElementById('totalValue').textContent =
            (currentPrice > 0 && qty > 0) ? 'Rp ' + (currentPrice * qty).toLocaleString('id-ID') : 'Rp 0';
    }

    function validateQty() {
        const qty = parseInt(document.getElementById('jumlah').value) || 0;
        const warn = document.getElementById('stockWarning');
        const inp  = document.getElementById('jumlah');
        const over = currentStock > 0 && qty > currentStock;
        warn.classList.toggle('hidden', !over);
        inp.classList.toggle('border-red-300', over);
        inp.classList.toggle('border-gray-300', !over);
    }

    // Trigger total on load if item already selected
    calculateTotal();
});
</script>
@endsection

@push('scripts')
    <x-popup id="update-modal-{{ $outgoingItem->id }}" title="Konfirmasi Update"
        message="Apakah Anda yakin ingin menyimpan perubahan data barang keluar ini?"
        formId="update-form-{{ $outgoingItem->id }}"
        confirmText="Simpan"
        confirmClass="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal"
        icon="info" />
@endpush
