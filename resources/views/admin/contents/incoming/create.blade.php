@extends('admin.layouts.dashboard')
@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Masuk</h1>
                    <p class="text-sm text-gray-600 mt-1">Catat barang yang masuk ke inventory</p>
                </div>
                <a href="{{ route($routePrefix . '.incoming.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    â† Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form id="incomingForm" action="{{ route($routePrefix . '.incoming.store') }}" method="POST">
            @csrf

            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Barang Masuk</h3>
            </div>

            <div class="px-6 py-6 space-y-6">

                {{-- Item list --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700">
                            Daftar Barang <span class="text-red-500">*</span>
                        </label>
                        <button type="button" id="addItemBtn"
                                class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            + Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-4"></div>

                    {{-- Summary --}}
                    <div id="summarySection" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-green-900 mb-3">Ringkasan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div><span class="text-green-600 font-medium">Total Item:</span> <span id="totalItems" class="ml-2 font-semibold text-green-900">0</span></div>
                            <div><span class="text-green-600 font-medium">Total Quantity:</span> <span id="totalQuantity" class="ml-2 font-semibold text-green-900">0</span></div>
                            <div><span class="text-green-600 font-medium">Total Nilai:</span> <span id="grandTotal" class="ml-2 font-semibold text-green-600">Rp 0</span></div>
                        </div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                              placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route($routePrefix . '.incoming.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" id="submitButton"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75">
                        <svg id="submitIcon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg id="submitSpinner" class="hidden w-4 h-4 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        <span id="submitText">Simpan Barang Masuk</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
/* ===== Searchable Dropdown ===== */
.ssd-wrapper { position: relative; width: 100%; }
.ssd-display {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db; border-radius: 0.375rem;
    background: white; cursor: pointer; font-size: 0.875rem; color: #374151;
    transition: border-color .15s, box-shadow .15s; text-align: left;
}
.ssd-display:hover { border-color: #6ee7b7; }
.ssd-display.open { border-color: #16a34a; box-shadow: 0 0 0 2px rgba(22,163,74,.2); }
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
.ssd-search {
    width: 100%; padding: 7px 10px; border: 1px solid #d1d5db;
    border-radius: .375rem; font-size: .8rem; outline: none;
}
.ssd-search:focus { border-color: #16a34a; box-shadow: 0 0 0 2px rgba(22,163,74,.15); }
.ssd-options { overflow-y: auto; flex: 1; }
.ssd-option { padding: 9px 12px; font-size: .875rem; cursor: pointer; color: #374151; transition: background .1s; }
.ssd-option:hover { background: #f0fdf4; color: #16a34a; }
.ssd-option.selected { background: #dcfce7; font-weight: 600; color: #15803d; }
.ssd-option.no-result { color: #9ca3af; font-style: italic; cursor: default; }
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {

    /* ===== Item data from server ===== */
    const itemsData = {!! json_encode($items->map(function($item) {
        return [
            'id'               => $item->id,
            'name'             => $item->nama,
            'stock_total'      => $item->stok_total,
            'stock_reguler'    => $item->stok_reguler,
            'stock_peminjaman' => $item->stok_peminjaman,
            'supplier'         => $item->supplier->nama ?? 'N/A',
            'category'         => $item->category->name ?? 'N/A',
            'price'            => $item->harga ?? 0,
            'type'             => $item->type->value,
            'code'             => 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
            'location_label'   => $item->location_label ?? '',
            'location_kode'    => $item->location_kode ?? '',
        ];
    })) !!};

    let itemCounter  = 0;
    let selectedItems = {};

    const itemsContainer  = document.getElementById('itemsContainer');
    const summarySection  = document.getElementById('summarySection');
    const elTotalItems    = document.getElementById('totalItems');
    const elTotalQty      = document.getElementById('totalQuantity');
    const elGrandTotal    = document.getElementById('grandTotal');

    /* ===== Generic SSD initializer ===== */
    function initSSD(wrapper, onSelect) {
        const display  = wrapper.querySelector('.ssd-display');
        const dropdown = wrapper.querySelector('.ssd-dropdown');
        const search   = wrapper.querySelector('.ssd-search');
        const opts     = wrapper.querySelector('.ssd-options');
        const dispTxt  = display.querySelector('.ssd-display-text');
        const hidden   = wrapper.querySelector('input[type="hidden"]');

        display.addEventListener('click', () => {
            const isOpen = dropdown.classList.contains('open');
            document.querySelectorAll('.ssd-dropdown.open').forEach(d => {
                d.classList.remove('open');
                d.closest('.ssd-wrapper').querySelector('.ssd-display').classList.remove('open');
            });
            if (!isOpen) {
                dropdown.classList.add('open'); display.classList.add('open');
                search.value = ''; doFilter(''); setTimeout(() => search.focus(), 30);
            }
        });

        function doFilter(q) {
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

        search.addEventListener('input', e => doFilter(e.target.value));
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
    }

    /* ===== Close on outside click ===== */
    document.addEventListener('click', e => {
        if (!e.target.closest('.ssd-wrapper')) {
            document.querySelectorAll('.ssd-dropdown.open').forEach(d => {
                d.classList.remove('open');
                d.closest('.ssd-wrapper').querySelector('.ssd-display').classList.remove('open');
            });
        }
    });

    /* ===== Add item row ===== */
    document.getElementById('addItemBtn').addEventListener('click', addItemRow);

    function addItemRow() {
        itemCounter++;
        const cnt   = itemCounter;
        const rowId = `item-row-${cnt}`;

        const div = document.createElement('div');
        div.className = 'bg-white border border-gray-200 rounded-lg p-4';
        div.id = rowId;

        // Build options HTML from itemsData
        const optionsHtml = itemsData.map(item => `
            <div class="ssd-option"
                 data-value="${item.id}"
                 data-name="${item.name}"
                 data-stock-total="${item.stock_total}"
                 data-stock-reguler="${item.stock_reguler}"
                 data-stock-peminjaman="${item.stock_peminjaman}"
                 data-price="${item.price}"
                 data-supplier="${item.supplier}"
                 data-category="${item.category}"
                 data-type="${item.type}"
                 data-code="${item.code}"
                 data-location-label="${item.location_label}"
                 data-location-kode="${item.location_kode}">
                ${item.name} <span style="color:#9ca3af;font-size:0.75rem">Â· ${item.code} Â· ${item.type === 'stok' ? '[STOK]' : '[PEMINJAMAN]'} Â· ${item.supplier}</span>
            </div>
        `).join('');

        div.innerHTML = `
            <div class="flex items-start justify-between mb-4">
                <h5 class="text-sm font-medium text-gray-900">Item #${cnt}</h5>
                <button type="button" onclick="removeItemRow('${rowId}')" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang</label>
                    <div class="flex gap-2 items-start">
                        <div class="ssd-wrapper flex-1" id="item-ssd-${cnt}">
                            <input type="hidden" name="items[${cnt}][item_id]" class="item-hidden" data-counter="${cnt}" required>
                            <button type="button" class="ssd-display" data-placeholder="-- Pilih Barang --">
                                <span class="ssd-display-text ssd-placeholder">-- Pilih Barang --</span>
                                <svg class="ssd-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="ssd-dropdown">
                                <div class="ssd-search-wrap">
                                    <input type="text" class="ssd-search" placeholder="ðŸ” Ketik nama barang, kode, atau supplier..." autocomplete="off">
                                </div>
                                <div class="ssd-options">
                                    <div class="ssd-option" data-value="">-- Pilih Barang --</div>
                                    ${optionsHtml}
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="openItemScan(${cnt})" title="Scan Barcode Barang"
                            class="flex-shrink-0 inline-flex items-center gap-1 px-3 py-2 bg-violet-600 text-white text-xs font-semibold rounded-lg hover:bg-violet-700 transition-colors shadow-sm" style="height:38px;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            Scan
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="items[${cnt}][quantity]"
                           class="quantity-input block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                           data-counter="${cnt}" min="1" placeholder="0" required>
                </div>
            </div>

            <div class="item-details-${cnt} hidden mt-4 p-3 bg-green-50 border border-green-200 rounded-md">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-sm">
                    <div><span class="text-green-600 font-medium">Tipe:</span> <span class="item-type-${cnt} ml-1 font-semibold text-green-900"></span></div>
                    <div><span class="text-green-600 font-medium">Stok:</span> <span class="item-stock-${cnt} ml-1 font-semibold text-green-600"></span></div>
                    <div><span class="text-green-600 font-medium">Supplier:</span> <span class="item-supplier-${cnt} ml-1 font-semibold text-green-900"></span></div>
                    <div><span class="text-green-600 font-medium">Harga:</span> <span class="item-price-${cnt} ml-1 font-semibold text-green-600"></span></div>
                    <div><span class="text-green-600 font-medium">Total:</span> <span class="item-total-${cnt} ml-1 font-semibold text-green-600">Rp 0</span></div>
                </div>
                <div class="item-location-row-${cnt} hidden mt-2 pt-2 border-t border-green-200">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-xs font-semibold text-indigo-500">Lokasi:</span>
                        <span class="item-location-${cnt} text-xs font-bold text-indigo-700"></span>
                        <span class="item-location-kode-${cnt} text-[10px] font-bold font-mono bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded-md"></span>
                    </div>
                </div>
            </div>
        `;

        itemsContainer.appendChild(div);

        // Init SSD for this row
        const wrapper = div.querySelector('.ssd-wrapper');
        initSSD(wrapper, (val, dataset) => handleItemSelect(cnt, val, dataset));

        div.querySelector('.quantity-input').addEventListener('input', function() {
            handleQuantityChange(this);
        });

        updateSummary();
    }

    function handleItemSelect(cnt, val, dataset) {
        const detailsDiv = document.querySelector(`.item-details-${cnt}`);
        detailsDiv.classList.toggle('hidden', !val);

        if (val) {
            const itemData = {
                id:               val,
                name:             dataset.name,
                stock_total:      parseInt(dataset.stockTotal)      || 0,
                stock_reguler:    parseInt(dataset.stockReguler)    || 0,
                stock_peminjaman: parseInt(dataset.stockPeminjaman) || 0,
                price:            parseFloat(dataset.price)         || 0,
                supplier:         dataset.supplier,
                category:         dataset.category,
                type:             dataset.type,
                location_label:   dataset.locationLabel || '',
                location_kode:    dataset.locationKode  || '',
            };
            selectedItems[cnt] = itemData;

            document.querySelector(`.item-type-${cnt}`).textContent     = itemData.type === 'stok' ? 'STOK' : 'PEMINJAMAN';
            document.querySelector(`.item-supplier-${cnt}`).textContent  = itemData.supplier;

            if (itemData.type === 'stok') {
                document.querySelector(`.item-stock-${cnt}`).textContent = itemData.stock_reguler + ' unit';
                document.querySelector(`.item-price-${cnt}`).textContent = itemData.price > 0 ? 'Rp ' + itemData.price.toLocaleString('id-ID') : 'Rp 0';
            } else {
                document.querySelector(`.item-stock-${cnt}`).textContent = itemData.stock_peminjaman + ' unit';
                document.querySelector(`.item-price-${cnt}`).textContent = 'N/A';
            }

            // Show location if available
            const locRow = document.querySelector(`.item-location-row-${cnt}`);
            if (itemData.location_label) {
                document.querySelector(`.item-location-${cnt}`).textContent      = itemData.location_label;
                document.querySelector(`.item-location-kode-${cnt}`).textContent = itemData.location_kode;
                document.querySelector(`.item-location-kode-${cnt}`).style.display = itemData.location_kode ? '' : 'none';
                locRow.classList.remove('hidden');
            } else {
                locRow.classList.add('hidden');
            }

            handleQuantityChange(document.querySelector(`input.quantity-input[data-counter="${cnt}"]`));
        } else {
            delete selectedItems[cnt];
        }
        updateSummary();
    }

    function handleQuantityChange(qInput) {
        const cnt      = qInput.dataset.counter;
        const quantity = parseInt(qInput.value) || 0;
        const itemData = selectedItems[cnt];

        if (itemData) {
            let total = 0;
            if (itemData.type === 'stok' && itemData.price > 0) total = quantity * itemData.price;
            document.querySelector(`.item-total-${cnt}`).textContent = total > 0 ? 'Rp ' + total.toLocaleString('id-ID') : 'Rp 0';
        }
        updateSummary();
    }

    function updateSummary() {
        const rows   = itemsContainer.querySelectorAll('[id^="item-row-"]');
        let totalQty = 0, grandTotalVal = 0;

        rows.forEach(row => {
            const q   = parseInt(row.querySelector('.quantity-input').value) || 0;
            const cnt = row.querySelector('.quantity-input').dataset.counter;
            const d   = selectedItems[cnt];
            if (d && q > 0) {
                totalQty += q;
                if (d.type === 'stok' && d.price > 0) grandTotalVal += q * d.price;
            }
        });

        elTotalItems.textContent  = rows.length;
        elTotalQty.textContent    = totalQty;
        elGrandTotal.textContent  = grandTotalVal > 0 ? 'Rp ' + grandTotalVal.toLocaleString('id-ID') : 'Rp 0';
        summarySection.classList.toggle('hidden', rows.length === 0);
    }

    window.removeItemRow = function(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            const cnt = row.querySelector('.item-hidden').dataset.counter;
            delete selectedItems[cnt];
            row.remove();
            updateSummary();
        }
    };

    // Start with one row
    addItemRow();

    // Submit with spinner
    const form         = document.getElementById('incomingForm');
    const submitButton = document.getElementById('submitButton');
    const submitText   = document.getElementById('submitText');
    const submitIcon   = document.getElementById('submitIcon');
    const submitSpinner= document.getElementById('submitSpinner');

    form.addEventListener('submit', function(e) {
        if (submitButton.disabled) { e.preventDefault(); return false; }
        submitButton.disabled = true;
        submitText.textContent = 'Menyimpan...';
        submitIcon.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
        return true;
    });

    @if($errors->any())
        submitButton.disabled = false;
        submitText.textContent = 'Simpan Barang Masuk';
        submitIcon.classList.remove('hidden');
        submitSpinner.classList.add('hidden');
    @endif
});
</script>
@endsection

{{-- Item Barcode Scanner Modal --}}
@include('admin.components.partials.item-scan-modal')

<script>
/**
 * Callback: pilih item di SSD dropdown incoming (item-ssd-N)
 */
window._isSelectCallback = function(item, cnt) {
    var opt = document.querySelector('#item-ssd-' + cnt + ' .ssd-option[data-value="' + item.id + '"]');
    if (opt) { opt.click(); }
};
</script>

