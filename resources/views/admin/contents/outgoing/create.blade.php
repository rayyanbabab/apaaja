@extends('admin.layouts.dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Keluar</h1>
                    <p class="text-sm text-gray-600 mt-1">Catat barang yang keluar dari inventory</p>
                </div>
                <a href="{{ route($routePrefix . '.outgoing.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    â† Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route($routePrefix . '.outgoing.store') }}" method="POST">
            @csrf

            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Barang Keluar</h3>
            </div>

            <div class="px-6 py-6 space-y-6">

                {{-- User Searchable --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih User <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2 items-start">
                        <div class="ssd-wrapper flex-1" id="user-ssd-wrapper">
                            <input type="hidden" name="user_select" id="user_select_hidden" required>
                            <button type="button" class="ssd-display" id="user-ssd-display">
                                <span class="ssd-display-text ssd-placeholder">-- Pilih User --</span>
                                <svg class="ssd-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="ssd-dropdown" id="user-ssd-dropdown">
                                <div class="ssd-search-wrap">
                                    <input type="text" class="ssd-search" placeholder="ðŸ” Ketik nama atau email user..." autocomplete="off">
                                </div>
                                <div class="ssd-options">
                                    <div class="ssd-option" data-value="">-- Pilih User --</div>
                                    @foreach($users as $user)
                                        <div class="ssd-option"
                                             data-value="{{ $user->id }}"
                                             data-name="{{ $user->name }}"
                                             data-email="{{ $user->email }}">
                                            {{ $user->name }}
                                            <span style="color:#9ca3af;font-size:0.75rem"> Â· ID:{{ $user->id }} Â· {{ $user->email }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- QR Scan Button --}}
                        <button type="button" onclick="openQrScanner('outgoing')"
                            title="Scan QR Code User"
                            class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-150 shadow-sm"
                            style="height:38px;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            Scan QR
                        </button>
                    </div>
                    @error('user_select')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Selected user info card --}}
                    <div id="selected_user_info" class="hidden mt-3 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">User Terpilih</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div><span class="text-blue-600 font-medium">Nama:</span> <span id="sel_user_name" class="ml-1 font-semibold text-blue-900"></span></div>
                            <div><span class="text-blue-600 font-medium">ID:</span> <span id="sel_user_id" class="ml-1 font-semibold text-blue-900"></span></div>
                            <div><span class="text-blue-600 font-medium">Email:</span> <span id="sel_user_email" class="ml-1 font-semibold text-blue-900"></span></div>
                        </div>
                    </div>
                    <input type="hidden" name="user_name" id="user_name">
                    <input type="hidden" name="user_id_input" id="user_id_input">
                </div>

                {{-- Item List --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700">
                            Daftar Barang <span class="text-red-500">*</span>
                        </label>
                        <button type="button" id="addItemBtn"
                                class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            + Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-4"></div>

                    <div id="summarySection" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Ringkasan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div><span class="text-gray-600 font-medium">Total Item:</span> <span id="totalItems" class="ml-2 font-semibold text-gray-900">0</span></div>
                            <div><span class="text-gray-600 font-medium">Total Quantity:</span> <span id="totalQuantity" class="ml-2 font-semibold text-gray-900">0</span></div>
                            <div><span class="text-gray-600 font-medium">Total Nilai:</span> <span id="grandTotal" class="ml-2 font-semibold text-green-600">Rp 0</span></div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="status" value="to_production">

                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                              placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan') }}</textarea>
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
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        âœ“ Simpan Barang Keluar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
/* === Searchable Select Dropdown === */
.ssd-wrapper { position: relative; width: 100%; }
.ssd-display {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db; border-radius: 0.375rem;
    background: white; cursor: pointer;
    font-size: 0.875rem; color: #374151;
    transition: border-color 0.15s, box-shadow 0.15s;
    text-align: left;
}
.ssd-display:hover { border-color: #93c5fd; }
.ssd-display.open { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.2); }
.ssd-placeholder { color: #9ca3af; }
.ssd-arrow { transition: transform 0.2s; color: #6b7280; flex-shrink: 0; margin-left: 8px; }
.ssd-display.open .ssd-arrow { transform: rotate(180deg); }
.ssd-dropdown {
    display: none; flex-direction: column;
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: white; border: 1px solid #d1d5db; border-radius: 0.375rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12); z-index: 9999;
    max-height: 300px; overflow: hidden;
}
.ssd-dropdown.open { display: flex; }
.ssd-search-wrap { padding: 8px; border-bottom: 1px solid #f3f4f6; flex-shrink: 0; }
.ssd-search {
    width: 100%; padding: 7px 10px;
    border: 1px solid #d1d5db; border-radius: 0.375rem;
    font-size: 0.8rem; outline: none;
}
.ssd-search:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); }
.ssd-options { overflow-y: auto; flex: 1; }
.ssd-option {
    padding: 9px 12px; font-size: 0.875rem;
    cursor: pointer; color: #374151; transition: background 0.1s;
}
.ssd-option:hover { background: #eff6ff; color: #1d4ed8; }
.ssd-option.selected { background: #dbeafe; font-weight: 600; color: #1e40af; }
.ssd-option.no-result { color: #9ca3af; font-style: italic; cursor: default; }
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {

   
    function initSSD(wrapper, onSelect) {
        const display    = wrapper.querySelector('.ssd-display');
        const dropdown   = wrapper.querySelector('.ssd-dropdown');
        const searchInp  = wrapper.querySelector('.ssd-search');
        const optsArea   = wrapper.querySelector('.ssd-options');
        const displayTxt = display.querySelector('.ssd-display-text');
        const hiddenInp  = wrapper.querySelector('input[type="hidden"]');

        function openDropdown() {
            // Close all others
            document.querySelectorAll('.ssd-dropdown.open').forEach(d => {
                d.classList.remove('open');
                d.closest('.ssd-wrapper').querySelector('.ssd-display').classList.remove('open');
            });
            dropdown.classList.add('open');
            display.classList.add('open');
            if (searchInp) { searchInp.value = ''; filterOpts(''); setTimeout(() => searchInp.focus(), 30); }
        }

        function closeDropdown() {
            dropdown.classList.remove('open');
            display.classList.remove('open');
        }

        display.addEventListener('click', function() {
            dropdown.classList.contains('open') ? closeDropdown() : openDropdown();
        });

        // Search filter
        function filterOpts(q) {
            q = q.toLowerCase();
            let any = false;
            optsArea.querySelectorAll('.ssd-option:not(.no-result)').forEach(opt => {
                if (!opt.dataset.value) { opt.style.display = ''; return; }
                const match = !q || opt.textContent.toLowerCase().includes(q);
                opt.style.display = match ? '' : 'none';
                if (match) any = true;
            });
            let nr = optsArea.querySelector('.no-result');
            if (!any && q) {
                if (!nr) { nr = document.createElement('div'); nr.className='ssd-option no-result'; nr.textContent='Tidak ditemukan'; optsArea.appendChild(nr); }
                nr.style.display = '';
            } else if (nr) { nr.style.display = 'none'; }
        }

        if (searchInp) {
            searchInp.addEventListener('input', e => filterOpts(e.target.value));
            searchInp.addEventListener('click', e => e.stopPropagation());
        }

        // Click option
        optsArea.querySelectorAll('.ssd-option').forEach(opt => {
            opt.addEventListener('click', function() {
                const val = this.dataset.value || '';
                hiddenInp.value = val;

                if (val) {
                    // Build display label: first text node only
                    const textNode = [...this.childNodes].find(n => n.nodeType === 3);
                    displayTxt.textContent = textNode ? textNode.textContent.trim() : this.textContent.trim();
                    displayTxt.classList.remove('ssd-placeholder');
                } else {
                    displayTxt.textContent = display.dataset.placeholder || '-- Pilih --';
                    displayTxt.classList.add('ssd-placeholder');
                }

                optsArea.querySelectorAll('.ssd-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                closeDropdown();

                if (onSelect) onSelect(val, this.dataset);
            });
        });
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


    /* =====================================================
       User SSD
    ===================================================== */
    const userWrapper = document.getElementById('user-ssd-wrapper');
    const userInfoCard = document.getElementById('selected_user_info');

    initSSD(userWrapper, function(val, dataset) {
        userInfoCard.classList.toggle('hidden', !val);
        if (val) {
            document.getElementById('sel_user_name').textContent  = dataset.name;
            document.getElementById('sel_user_id').textContent    = val;
            document.getElementById('sel_user_email').textContent = dataset.email;
            document.getElementById('user_name').value            = dataset.name;
            document.getElementById('user_id_input').value        = val;
        } else {
            document.getElementById('user_name').value    = '';
            document.getElementById('user_id_input').value = '';
        }
    });


    /* =====================================================
       Item Rows
    ===================================================== */
    const itemsContainer = document.getElementById('itemsContainer');
    const summarySection = document.getElementById('summarySection');
    const elTotalItems    = document.getElementById('totalItems');
    const elTotalQty      = document.getElementById('totalQuantity');
    const elGrandTotal    = document.getElementById('grandTotal');

    let itemCounter  = 0;
    let selectedItems = {};

    const itemsData = {!! json_encode($items->map(function($item) {
        return [
            'id'             => $item->id,
            'name'           => $item->nama,
            'stock'          => $item->stok_total,
            'supplier'       => $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A',
            'category'       => $item->category->name ?? 'N/A',
            'code'           => 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
            'price'          => $item->harga ?? 0,
            'location_label' => $item->location_label ?? '',
            'location_kode'  => $item->location_kode ?? '',
        ];
    })) !!};

    document.getElementById('addItemBtn').addEventListener('click', addItemRow);

    function addItemRow() {
        itemCounter++;
        const rowId = `item-row-${itemCounter}`;
        const cnt   = itemCounter;

        const div = document.createElement('div');
        div.className = 'bg-white border border-gray-200 rounded-lg p-4';
        div.id = rowId;

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
                        <div class="ssd-wrapper item-ssd-wrapper flex-1" id="item-ssd-wrapper-${cnt}">
                            <input type="hidden" name="items[${cnt}][item_id]" class="item-hidden-input" data-counter="${cnt}" required>
                            <button type="button" class="ssd-display" data-placeholder="-- Pilih Barang --">
                                <span class="ssd-display-text ssd-placeholder">-- Pilih Barang --</span>
                                <svg class="ssd-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="ssd-dropdown">
                                <div class="ssd-search-wrap">
                                    <input type="text" class="ssd-search" placeholder="ðŸ” Ketik nama barang, kode, atau supplier..." autocomplete="off">
                                </div>
                                <div class="ssd-options">
                                    <div class="ssd-option" data-value="">-- Pilih Barang --</div>
                                    ${itemsData.map(item => `
                                        <div class="ssd-option"
                                             data-value="${item.id}"
                                             data-stock="${item.stock}"
                                             data-price="${item.price}"
                                             data-name="${item.name}"
                                             data-code="${item.code}"
                                             data-supplier="${item.supplier}"
                                             data-category="${item.category}"
                                             data-location-label="${item.location_label}"
                                             data-location-kode="${item.location_kode}">
                                            ${item.name}
                                            <span style="color:#9ca3af;font-size:0.75rem"> Â· ${item.code} Â· Stok: ${item.stock} Â· ${item.supplier}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="openItemScan(${cnt}, 'stok')" title="Scan Barcode Barang Stok"
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
                           class="quantity-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           data-counter="${cnt}" min="1" placeholder="0" required>
                    <p class="stock-warning-${cnt} mt-1 text-sm text-red-600 hidden">âš  Melebihi stok tersedia!</p>
                </div>
            </div>

            <div class="item-details-${cnt} hidden mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    <div><span class="text-blue-600 font-medium">Kode:</span> <span class="item-code-${cnt} ml-1 font-semibold text-blue-900"></span></div>
                    <div><span class="text-blue-600 font-medium">Stok:</span> <span class="item-stock-${cnt} ml-1 font-semibold text-green-600"></span></div>
                    <div><span class="text-blue-600 font-medium">Harga:</span> <span class="item-price-${cnt} ml-1 font-semibold text-green-600"></span></div>
                    <div><span class="text-blue-600 font-medium">Total:</span> <span class="item-total-${cnt} ml-1 font-semibold text-green-600">Rp 0</span></div>
                </div>
                <div class="item-location-row-${cnt} hidden mt-2 pt-2 border-t border-blue-200">
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

        // Init item SSD
        const itemWrapper = div.querySelector('.item-ssd-wrapper');
        const hiddenInp   = div.querySelector('.item-hidden-input');

        initSSD(itemWrapper, function(val, dataset) {
            handleItemSelection(cnt, val, dataset);
        });

        div.querySelector('.quantity-input').addEventListener('input', function() {
            handleQuantityChange(this);
        });

        updateSummary();
    }

    function handleItemSelection(cnt, val, dataset) {
        const detailsDiv = document.querySelector(`.item-details-${cnt}`);
        detailsDiv.classList.toggle('hidden', !val);

        if (val) {
            const itemData = {
                id:            val,
                name:          dataset.name,
                stock:         parseInt(dataset.stock) || 0,
                price:         parseFloat(dataset.price) || 0,
                code:          dataset.code,
                supplier:      dataset.supplier,
                category:      dataset.category,
                locationLabel: dataset.locationLabel || '',
                locationKode:  dataset.locationKode  || '',
            };
            selectedItems[cnt] = itemData;

            document.querySelector(`.item-code-${cnt}`).textContent  = itemData.code;
            document.querySelector(`.item-stock-${cnt}`).textContent  = itemData.stock + ' unit';
            document.querySelector(`.item-price-${cnt}`).textContent  = itemData.price > 0 ? 'Rp ' + itemData.price.toLocaleString('id-ID') : 'Rp 0';

            // Location badge
            const locRow = document.querySelector(`.item-location-row-${cnt}`);
            if (itemData.locationLabel) {
                document.querySelector(`.item-location-${cnt}`).textContent      = itemData.locationLabel;
                document.querySelector(`.item-location-kode-${cnt}`).textContent = itemData.locationKode;
                document.querySelector(`.item-location-kode-${cnt}`).style.display = itemData.locationKode ? '' : 'none';
                locRow.classList.remove('hidden');
            } else {
                locRow.classList.add('hidden');
            }

            const qInput = document.querySelector(`input.quantity-input[data-counter="${cnt}"]`);
            qInput.max = itemData.stock;
            handleQuantityChange(qInput);
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
            const total = quantity * itemData.price;
            document.querySelector(`.item-total-${cnt}`).textContent = total > 0 ? 'Rp ' + total.toLocaleString('id-ID') : 'Rp 0';

            const overStock = quantity > itemData.stock;
            document.querySelector(`.stock-warning-${cnt}`).classList.toggle('hidden', !overStock);
            qInput.classList.toggle('border-red-300', overStock);
            qInput.classList.toggle('border-gray-300', !overStock);
        }
        updateSummary();
    }

    function updateSummary() {
        const rows = itemsContainer.querySelectorAll('[id^="item-row-"]');
        let totalQ = 0, grandTotalVal = 0;

        rows.forEach(row => {
            const q    = parseInt(row.querySelector('.quantity-input').value) || 0;
            const cnt  = row.querySelector('.quantity-input').dataset.counter;
            const data = selectedItems[cnt];
            if (data && q > 0) { totalQ += q; grandTotalVal += q * data.price; }
        });

        elTotalItems.textContent  = rows.length;
        elTotalQty.textContent    = totalQ;
        elGrandTotal.textContent  = grandTotalVal > 0 ? 'Rp ' + grandTotalVal.toLocaleString('id-ID') : 'Rp 0';
        summarySection.classList.toggle('hidden', rows.length === 0);
    }

    window.removeItemRow = function(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            const cnt = row.querySelector('.item-hidden-input').dataset.counter;
            delete selectedItems[cnt];
            row.remove();
            updateSummary();
        }
    };

    addItemRow();
});
</script>

{{-- QR Scanner Modal --}}
@include('admin.components.partials.qr-scanner-modal')

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
window.onQrUserFound = function(userData) {
    // Find the option in the outgoing user SSD
    var userWrapper = document.getElementById('user-ssd-wrapper');
    var opts = userWrapper.querySelectorAll('.ssd-options .ssd-option[data-value]');
    var found = null;
    opts.forEach(function(opt) {
        if (String(opt.dataset.value) === String(userData.id)) found = opt;
    });
    if (found) {
        found.click();
    } else {
        showQrError('User dengan ID ' + userData.id + ' tidak ditemukan di sistem.');
    }
};
</script>
@endpush
@endsection


{{-- Item Barcode Scanner Modal --}}
@include('admin.components.partials.item-scan-modal')

<script>
/**
 * Callback: pilih item di SSD dropdown outgoing (item-ssd-wrapper-N)
 */
window._isSelectCallback = function(item, cnt) {
    var wrapper = document.getElementById('item-ssd-wrapper-' + cnt);
    if (!wrapper) return;
    var opt = wrapper.querySelector('.ssd-option[data-value="' + item.id + '"]');
    if (opt) { opt.click(); }
};
</script>
