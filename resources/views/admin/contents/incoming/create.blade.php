@extends('admin.layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Masuk</h1>
                    <p class="text-sm text-gray-600 mt-1">Catat barang yang masuk ke inventory</p>
                </div>
                <a href="{{ route('admin.incoming.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form id="incomingForm" action="{{ route('admin.incoming.store') }}" method="POST">
            @csrf
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Barang Masuk</h3>
            </div>

            <div class="px-6 py-6 space-y-6">
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
                            Tambah Item
                        </button>
                    </div>
                    <div id="itemsContainer" class="space-y-4">
                    </div>
                    <div id="summarySection" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-green-900 mb-3">Ringkasan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-green-600 font-medium">Total Item:</span>
                                <span id="totalItems" class="ml-2 font-semibold text-green-900">0</span>
                            </div>
                            <div>
                                <span class="text-green-600 font-medium">Total Quantity:</span>
                                <span id="totalQuantity" class="ml-2 font-semibold text-green-900">0</span>
                            </div>
                            <div>
                                <span class="text-green-600 font-medium">Total Nilai:</span>
                                <span id="grandTotal" class="ml-2 font-semibold text-green-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
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
                    <a href="{{ route('admin.incoming.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" 
                            id="submitButton"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75">
                        <svg id="submitIcon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span id="submitText">Simpan Barang Masuk</span>
                        <svg id="submitSpinner" class="hidden w-4 h-4 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const elements = {
        addItemBtn: document.getElementById('addItemBtn'),
        itemsContainer: document.getElementById('itemsContainer'),
        summarySection: document.getElementById('summarySection'),
        totalItems: document.getElementById('totalItems'),
        totalQuantity: document.getElementById('totalQuantity'),
        grandTotal: document.getElementById('grandTotal')
    };

    let itemCounter = 0;
    let selectedItems = {};
    const itemsData = {!! json_encode($items->map(function($item) {
        return [
            'id' => $item->id,
            'name' => $item->nama,
            'stock_total' => $item->stok_total,
            'stock_reguler' => $item->stok_reguler,
            'stock_peminjaman' => $item->stok_peminjaman,
            'supplier' => $item->supplier->nama ?? 'N/A',
            'category' => $item->category->name ?? 'N/A',
            'price' => $item->harga ?? 0,
            'type' => $item->type->value
        ];
    })) !!};
    elements.addItemBtn.addEventListener('click', function() {
        addItemRow();
    });

    function addItemRow() {
        itemCounter++;
        const rowId = `item-row-${itemCounter}`;
        
        const itemRow = document.createElement('div');
        itemRow.className = 'bg-white border border-gray-200 rounded-lg p-4';
        itemRow.id = rowId;
        
        itemRow.innerHTML = `
            <div class="flex items-start justify-between mb-4">
                <h5 class="text-sm font-medium text-gray-900">Item #${itemCounter}</h5>
                <button type="button" onclick="removeItemRow('${rowId}')" 
                        class="text-red-600 hover:text-red-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang</label>
                    <select name="items[${itemCounter}][item_id]" class="item-select block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" data-counter="${itemCounter}" required>
                        <option value="">-- Pilih Barang --</option>
                        ${itemsData.map(item => `
                            <option value="${item.id}" 
                                    data-stock-total="${item.stock_total}"
                                    data-stock-reguler="${item.stock_reguler}"
                                    data-stock-peminjaman="${item.stock_peminjaman}"
                                    data-price="${item.price}"
                                    data-name="${item.name}"
                                    data-supplier="${item.supplier}"
                                    data-category="${item.category}"
                                    data-type="${item.type}">
                                ${item.name} - ${item.type === 'stok' ? '[STOK]' : '[PEMINJAMAN]'} - ${item.supplier}
                            </option>
                        `).join('')}
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="items[${itemCounter}][quantity]" 
                           class="quantity-input block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" 
                           data-counter="${itemCounter}" min="1" placeholder="0" required>
                </div>
            </div>
            
            <div class="item-details-${itemCounter} hidden mt-4 p-3 bg-green-50 border border-green-200 rounded-md">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 text-sm">
                    <div>
                        <span class="text-green-600 font-medium">Tipe:</span>
                        <span class="item-type-${itemCounter} ml-1 font-semibold text-green-900"></span>
                    </div>
                    <div>
                        <span class="text-green-600 font-medium">Stok:</span>
                        <span class="item-stock-${itemCounter} ml-1 font-semibold text-green-600"></span>
                    </div>
                    <div>
                        <span class="text-green-600 font-medium">Supplier:</span>
                        <span class="item-supplier-${itemCounter} ml-1 font-semibold text-green-900"></span>
                    </div>
                    <div>
                        <span class="text-green-600 font-medium">Harga:</span>
                        <span class="item-price-${itemCounter} ml-1 font-semibold text-green-600"></span>
                    </div>
                    <div>
                        <span class="text-green-600 font-medium">Total:</span>
                        <span class="item-total-${itemCounter} ml-1 font-semibold text-green-600">Rp 0</span>
                    </div>
                </div>
            </div>
        `;
        
        elements.itemsContainer.appendChild(itemRow);
        
        const itemSelect = itemRow.querySelector('.item-select');
        const quantityInput = itemRow.querySelector('.quantity-input');
        
        itemSelect.addEventListener('change', function() {
            handleItemSelection(this);
        });
        
        quantityInput.addEventListener('input', function() {
            handleQuantityChange(this);
        });
        
        updateSummary();
    }

    function handleItemSelection(selectElement) {
        const counter = selectElement.dataset.counter;
        const option = selectElement.options[selectElement.selectedIndex];
        const hasValue = option.value;
        
        const detailsDiv = document.querySelector(`.item-details-${counter}`);
        detailsDiv.classList.toggle('hidden', !hasValue);
        
        if (hasValue) {
            const itemData = {
                id: option.value,
                name: option.dataset.name,
                stock_total: parseInt(option.dataset.stockTotal) || 0,
                stock_reguler: parseInt(option.dataset.stockReguler) || 0,
                stock_peminjaman: parseInt(option.dataset.stockPeminjaman) || 0,
                price: parseFloat(option.dataset.price) || 0,
                supplier: option.dataset.supplier,
                category: option.dataset.category,
                type: option.dataset.type
            };
            
            selectedItems[counter] = itemData;
            
            document.querySelector(`.item-type-${counter}`).textContent = itemData.type === 'stok' ? 'STOK' : 'PEMINJAMAN';
            document.querySelector(`.item-supplier-${counter}`).textContent = itemData.supplier;
            
            if (itemData.type === 'stok') {
                document.querySelector(`.item-stock-${counter}`).textContent = itemData.stock_reguler + ' unit (Reguler)';
                document.querySelector(`.item-price-${counter}`).textContent = itemData.price > 0 ? 'Rp ' + itemData.price.toLocaleString('id-ID') : 'Rp 0';
            } else {
                document.querySelector(`.item-stock-${counter}`).textContent = itemData.stock_peminjaman + ' unit (Peminjaman)';
                document.querySelector(`.item-price-${counter}`).textContent = 'N/A (Peminjaman)';
            }
            
            handleQuantityChange(document.querySelector(`input[data-counter="${counter}"]`));
        } else {
            delete selectedItems[counter];
        }
        
        updateSummary();
    }

    function handleQuantityChange(quantityInput) {
        const counter = quantityInput.dataset.counter;
        const quantity = parseInt(quantityInput.value) || 0;
        const itemData = selectedItems[counter];
        
        if (itemData) {
            let total = 0;
            if (itemData.type === 'stok' && itemData.price > 0) {
                total = quantity * itemData.price;
            }
            document.querySelector(`.item-total-${counter}`).textContent = total > 0 ? 'Rp ' + total.toLocaleString('id-ID') : 'Rp 0';
        }
        
        updateSummary();
    }

    function updateSummary() {
        const itemRows = elements.itemsContainer.querySelectorAll('[id^="item-row-"]');
        let totalItems = itemRows.length;
        let totalQuantity = 0;
        let grandTotalValue = 0;
        
        itemRows.forEach(row => {
            const quantityInput = row.querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value) || 0;
            const counter = quantityInput.dataset.counter;
            const itemData = selectedItems[counter];
            
            if (itemData && quantity > 0) {
                totalQuantity += quantity;
                if (itemData.type === 'stok' && itemData.price > 0) {
                    grandTotalValue += quantity * itemData.price;
                }
            }
        });
        
        elements.totalItems.textContent = totalItems;
        elements.totalQuantity.textContent = totalQuantity;
        elements.grandTotal.textContent = grandTotalValue > 0 ? 'Rp ' + grandTotalValue.toLocaleString('id-ID') : 'Rp 0';
        
        elements.summarySection.classList.toggle('hidden', totalItems === 0);
    }

    window.removeItemRow = function(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            const counter = row.querySelector('.item-select').dataset.counter;
            delete selectedItems[counter];
            row.remove();
            updateSummary();
        }
    };

    addItemRow();

    const form = document.getElementById('incomingForm');
    const submitButton = document.getElementById('submitButton');
    const submitText = document.getElementById('submitText');
    const submitIcon = document.getElementById('submitIcon');
    const submitSpinner = document.getElementById('submitSpinner');

    form.addEventListener('submit', function(e) {
        if (submitButton.disabled) {
            e.preventDefault();
            return false;
        }

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
