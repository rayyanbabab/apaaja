@extends('admin.layouts.dashboard')
@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Barang Masuk</h1>
                        <p class="text-sm text-gray-600 mt-1">Edit data barang masuk #{{ $incomingItem->id }}</p>
                    </div>
                    <a href="{{ route($routePrefix . '.incoming.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Current info card --}}
        <div class="bg-blue-50 rounded-lg border border-blue-200">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium text-blue-900 mb-2">Informasi Saat Ini</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div><span class="text-blue-700">Barang:</span> <span
                            class="ml-2 font-medium text-blue-900">{{ $incomingItem->item->nama }}</span></div>
                    <div><span class="text-blue-700">Jumlah Masuk:</span> <span
                            class="ml-2 font-medium text-blue-900">{{ $incomingItem->jumlah }}</span></div>
                    <div><span class="text-blue-700">Tanggal:</span> <span
                            class="ml-2 font-medium text-blue-900">{{ $incomingItem->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form id="update-form-{{ $incomingItem->id }}" action="{{ route($routePrefix . '.incoming.update', $incomingItem) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Edit Informasi Barang Masuk</h3>
                </div>

                <div class="px-6 py-6 space-y-6">

                    {{-- Searchable Item Select --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Barang <span class="text-red-500">*</span>
                        </label>

                        <div class="ssd-wrapper" id="item-ssd-wrapper">
                            <input type="hidden" name="item_id" id="item_id_hidden"
                                value="{{ old('item_id', $incomingItem->item_id) }}" required>
                            <button type="button" class="ssd-display" data-placeholder="-- Pilih Barang --">
                                <span
                                    class="ssd-display-text {{ old('item_id', $incomingItem->item_id) ? '' : 'ssd-placeholder' }}">
                                    @php
                                        $selected = $items->firstWhere('id', old('item_id', $incomingItem->item_id));
                                    @endphp
                                    {{ $selected ? $selected->nama . ' — ' . ('ITM-' . str_pad($selected->id, 4, '0', STR_PAD_LEFT)) : '-- Pilih Barang --' }}
                                </span>
                                <svg class="ssd-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="ssd-dropdown">
                                <div class="ssd-search-wrap">
                                    <input type="text" class="ssd-search"
                                        placeholder="🔍 Ketik nama barang, kode, atau supplier..." autocomplete="off">
                                </div>
                                <div class="ssd-options">
                                    <div class="ssd-option" data-value="">-- Pilih Barang --</div>
                                    @foreach($items as $item)
                                        <div class="ssd-option {{ old('item_id', $incomingItem->item_id) == $item->id ? 'selected' : '' }}"
                                            data-value="{{ $item->id }}" data-name="{{ $item->nama }}"
                                            data-stock="{{ $item->stok_total }}" data-stock-reguler="{{ $item->stok_reguler }}"
                                            data-stock-peminjaman="{{ $item->stok_peminjaman }}"
                                            data-code="ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}"
                                            data-supplier="{{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }}"
                                            data-category="{{ $item->category->name ?? 'N/A' }}"
                                            data-price="{{ $item->harga ?? 0 }}" data-type="{{ $item->type->value }}"
                                            data-description="{{ $item->keterangan ?? '' }}">
                                            {{ $item->nama }}
                                            <span style="color:#9ca3af;font-size:0.75rem">
                                                · ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                                · {{ $item->type->value === 'stok' ? '[STOK]' : '[PEMINJAMAN]' }}
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
                    <div id="itemDetails"
                        class="{{ old('item_id', $incomingItem->item_id) ? '' : 'hidden' }} bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-green-900 mb-3">Detail Barang</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><span class="text-green-600 font-medium">Kode Barang:</span> <span id="itemCode"
                                    class="ml-2 font-semibold text-green-900">{{ $selected ? 'ITM-' . str_pad($selected->id, 4, '0', STR_PAD_LEFT) : '' }}</span>
                            </div>
                            <div><span class="text-green-600 font-medium">Supplier:</span> <span id="supplierName"
                                    class="ml-2 font-semibold text-green-900">{{ $selected?->supplier->company_name ?? $selected?->supplier->nama ?? '' }}</span>
                            </div>
                            <div><span class="text-green-600 font-medium">Kategori:</span> <span id="categoryName"
                                    class="ml-2 font-semibold text-green-900">{{ $selected?->category->name ?? '' }}</span>
                            </div>
                            <div><span class="text-green-600 font-medium" id="stockLabel">Stok Tersedia:</span> <span
                                    id="availableStock"
                                    class="ml-2 font-semibold text-blue-600">{{ $selected ? $selected->stok_total : '' }}</span>
                            </div>
                            <div id="priceSection"
                                style="{{ ($selected && $selected->type->value === 'stok') ? '' : 'display:none' }}">
                                <span class="text-green-600 font-medium">Harga Satuan:</span>
                                <span id="itemPrice"
                                    class="ml-2 font-semibold text-green-600">{{ $selected && $selected->harga ? 'Rp ' . number_format($selected->harga, 0, ',', '.') : 'Rp 0' }}</span>
                            </div>
                            <div><span class="text-green-600 font-medium">Tipe Barang:</span>
                                <span id="itemTypeBadge"
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selected && $selected->type->value === 'stok' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $selected ? strtoupper($selected->type->value) : '' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-green-200">
                            <span class="text-green-600 font-medium text-sm">Deskripsi:</span>
                            <p id="itemDescription" class="mt-1 text-sm text-green-800 italic">
                                {{ $selected?->keterangan ?? 'Tidak ada deskripsi' }}
                            </p>
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah" id="jumlah" min="1" required
                            value="{{ old('jumlah', $incomingItem->jumlah) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                            placeholder="Masukkan jumlah barang yang masuk">
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500"><strong>Catatan:</strong> Mengubah jumlah akan menyesuaikan
                            stok barang secara otomatis.</p>
                    </div>

                    {{-- Stock impact --}}
                    <div id="stockImpact" class="hidden bg-yellow-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-yellow-800 mb-2">Dampak Perubahan Stok</h4>
                        <div class="text-sm text-yellow-700 space-y-1">
                            <div>Jumlah sebelumnya: <span id="oldQuantity" class="font-medium"></span></div>
                            <div>Jumlah baru: <span id="newQuantity" class="font-medium"></span></div>
                            <div>Perubahan stok: <span id="stockChange" class="font-medium"></span></div>
                        </div>
                    </div>

                    {{-- Total value --}}
                    <div id="totalValueCard" class="hidden bg-green-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-green-700">Total Nilai Barang Masuk:</span>
                            <span id="totalAmount" class="text-lg font-bold text-green-900"></span>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan
                            (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                            placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan', $incomingItem->keterangan) }}</textarea>
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
                        <button type="button" onclick="openModal('update-modal-{{ $incomingItem->id }}')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Barang Masuk
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .ssd-wrapper {
                position: relative;
                width: 100%;
            }

            .ssd-display {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                padding: 0.5rem 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                background: white;
                cursor: pointer;
                font-size: 0.875rem;
                color: #374151;
                transition: border-color 0.15s, box-shadow 0.15s;
                text-align: left;
            }

            .ssd-display:hover {
                border-color: #6ee7b7;
            }

            .ssd-display.open {
                border-color: #16a34a;
                box-shadow: 0 0 0 2px rgba(22, 163, 74, .2);
            }

            .ssd-placeholder {
                color: #9ca3af;
            }

            .ssd-arrow {
                transition: transform 0.2s;
                color: #6b7280;
                flex-shrink: 0;
                margin-left: 8px;
            }

            .ssd-display.open .ssd-arrow {
                transform: rotate(180deg);
            }

            .ssd-dropdown {
                display: none;
                flex-direction: column;
                position: absolute;
                top: calc(100% + 4px);
                left: 0;
                right: 0;
                background: white;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
                z-index: 9999;
                max-height: 300px;
                overflow: hidden;
            }

            .ssd-dropdown.open {
                display: flex;
            }

            .ssd-search-wrap {
                padding: 8px;
                border-bottom: 1px solid #f3f4f6;
                flex-shrink: 0;
            }

            .ssd-search {
                width: 100%;
                padding: 7px 10px;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                font-size: 0.8rem;
                outline: none;
            }

            .ssd-search:focus {
                border-color: #16a34a;
                box-shadow: 0 0 0 2px rgba(22, 163, 74, .15);
            }

            .ssd-options {
                overflow-y: auto;
                flex: 1;
            }

            .ssd-option {
                padding: 9px 12px;
                font-size: 0.875rem;
                cursor: pointer;
                color: #374151;
                transition: background 0.1s;
            }

            .ssd-option:hover {
                background: #f0fdf4;
                color: #16a34a;
            }

            .ssd-option.selected {
                background: #dcfce7;
                font-weight: 600;
                color: #15803d;
            }

            .ssd-option.no-result {
                color: #9ca3af;
                font-style: italic;
                cursor: default;
            }
        </style>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const originalQuantity = {{ $incomingItem->jumlah }};
            let currentPrice = 0;
            let currentType = '';

            /* ===== Searchable Dropdown ===== */
            const wrapper = document.getElementById('item-ssd-wrapper');
            const display = wrapper.querySelector('.ssd-display');
            const dropdown = wrapper.querySelector('.ssd-dropdown');
            const searchInp = wrapper.querySelector('.ssd-search');
            const optsArea = wrapper.querySelector('.ssd-options');
            const dispTxt = display.querySelector('.ssd-display-text');
            const hiddenInp = document.getElementById('item_id_hidden');

            display.addEventListener('click', function () {
                const isOpen = dropdown.classList.contains('open');
                dropdown.classList.toggle('open', !isOpen);
                display.classList.toggle('open', !isOpen);
                if (!isOpen) { searchInp.value = ''; filterOpts(''); setTimeout(() => searchInp.focus(), 30); }
            });

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
                    if (!nr) { nr = document.createElement('div'); nr.className = 'ssd-option no-result'; nr.textContent = 'Tidak ditemukan'; optsArea.appendChild(nr); }
                    nr.style.display = '';
                } else if (nr) { nr.style.display = 'none'; }
            }

            searchInp.addEventListener('input', e => filterOpts(e.target.value));
            searchInp.addEventListener('click', e => e.stopPropagation());

            optsArea.querySelectorAll('.ssd-option').forEach(opt => {
                opt.addEventListener('click', function () {
                    const val = this.dataset.value || '';
                    hiddenInp.value = val;

                    if (val) {
                        const textNode = [...this.childNodes].find(n => n.nodeType === 3);
                        dispTxt.textContent = (textNode ? textNode.textContent.trim() : this.textContent.trim());
                        dispTxt.classList.remove('ssd-placeholder');
                    } else {
                        dispTxt.textContent = '-- Pilih Barang --';
                        dispTxt.classList.add('ssd-placeholder');
                    }

                    optsArea.querySelectorAll('.ssd-option').forEach(o => o.classList.remove('selected'));
                    this.classList.add('selected');
                    dropdown.classList.remove('open'); display.classList.remove('open');

                    onItemSelect(val, this.dataset);
                });
            });

            document.addEventListener('click', e => {
                if (!e.target.closest('.ssd-wrapper')) {
                    dropdown.classList.remove('open'); display.classList.remove('open');
                }
            });

            /* ===== Item Selection Logic ===== */
            function onItemSelect(val, dataset) {
                const detailsDiv = document.getElementById('itemDetails');
                if (!val) { detailsDiv.classList.add('hidden'); currentPrice = 0; currentType = ''; calculateTotal(); return; }

                detailsDiv.classList.remove('hidden');
                currentType = dataset.type;
                document.getElementById('itemCode').textContent = dataset.code || 'N/A';
                document.getElementById('supplierName').textContent = dataset.supplier || 'N/A';
                document.getElementById('categoryName').textContent = dataset.category || 'N/A';
                document.getElementById('itemDescription').textContent = dataset.description || 'Tidak ada deskripsi';

                const badge = document.getElementById('itemTypeBadge');
                if (currentType === 'stok') {
                    document.getElementById('availableStock').textContent = (parseInt(dataset.stockReguler) || 0) + ' unit';
                    document.getElementById('priceSection').style.display = '';
                    currentPrice = parseFloat(dataset.price) || 0;
                    document.getElementById('itemPrice').textContent = currentPrice > 0 ? 'Rp ' + currentPrice.toLocaleString('id-ID') : 'Rp 0';
                    badge.textContent = 'STOK';
                    badge.className = 'ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                } else {
                    document.getElementById('availableStock').textContent = (parseInt(dataset.stockPeminjaman) || 0) + ' unit';
                    document.getElementById('priceSection').style.display = 'none';
                    currentPrice = 0;
                    badge.textContent = 'PEMINJAMAN';
                    badge.className = 'ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800';
                }
                calculateTotal(); calculateStockImpact();
            }

            /* ===== Quantity Events ===== */
            document.getElementById('jumlah').addEventListener('input', function () {
                calculateTotal(); calculateStockImpact();
            });

            function calculateTotal() {
                const qty = parseInt(document.getElementById('jumlah').value) || 0;
                const card = document.getElementById('totalValueCard');
                if (currentPrice > 0 && qty > 0) {
                    document.getElementById('totalAmount').textContent = 'Rp ' + (currentPrice * qty).toLocaleString('id-ID');
                    card.classList.remove('hidden');
                } else { card.classList.add('hidden'); }
            }

            function calculateStockImpact() {
                const newQty = parseInt(document.getElementById('jumlah').value) || 0;
                const card = document.getElementById('stockImpact');
                if (newQty !== originalQuantity) {
                    const diff = newQty - originalQuantity;
                    document.getElementById('oldQuantity').textContent = originalQuantity;
                    document.getElementById('newQuantity').textContent = newQty;
                    const el = document.getElementById('stockChange');
                    el.textContent = diff > 0 ? '+' + diff + ' (Stok bertambah)' : diff + ' (Stok berkurang)';
                    el.className = 'font-medium ' + (diff > 0 ? 'text-green-600' : 'text-red-600');
                    card.classList.remove('hidden');
                } else { card.classList.add('hidden'); }
            }

            // Init selected item on load
            if (hiddenInp.value) {
                const sel = optsArea.querySelector(`.ssd-option[data-value="${hiddenInp.value}"]`);
                if (sel) onItemSelect(hiddenInp.value, sel.dataset);
            }
        });
    </script>
@endsection

@push('scripts')
    <x-popup id="update-modal-{{ $incomingItem->id }}" title="Konfirmasi Update"
        message="Apakah Anda yakin ingin menyimpan perubahan data barang masuk ini?"
        formId="update-form-{{ $incomingItem->id }}"
        confirmText="Simpan"
        confirmClass="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal"
        icon="info" />
@endpush