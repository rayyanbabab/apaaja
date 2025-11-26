@extends('admin.layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Barang Masuk</h1>
                    <p class="text-sm text-gray-600 mt-1">Edit data barang masuk #{{ $incomingItem->id }}</p>
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
    <div class="bg-blue-50 rounded-lg border border-blue-200">
        <div class="px-6 py-4">
            <h3 class="text-lg font-medium text-blue-900 mb-2">Informasi Saat Ini</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-blue-700">Barang:</span>
                    <span class="ml-2 font-medium text-blue-900">{{ $incomingItem->item->nama }}</span>
                </div>
                <div>
                    <span class="text-blue-700">Jumlah Masuk:</span>
                    <span class="ml-2 font-medium text-blue-900">{{ $incomingItem->jumlah }}</span>
                </div>
                <div>
                    <span class="text-blue-700">Tanggal:</span>
                    <span class="ml-2 font-medium text-blue-900">{{ $incomingItem->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('admin.incoming.update', $incomingItem) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Edit Informasi Barang Masuk</h3>
            </div>

            <div class="px-6 py-6 space-y-6">
                <div>
                    <label for="item_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="item_id" id="item_id" required 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" 
                                    data-stock="{{ $item->stok_total }}"
                                    data-stock-reguler="{{ $item->stok_reguler }}"
                                    data-stock-peminjaman="{{ $item->stok_peminjaman }}"
                                    data-code="{{ $item->id ? 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}"
                                    data-supplier="{{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }}"
                                    data-category="{{ $item->category->name ?? 'N/A' }}"
                                    data-price="{{ $item->harga ?? 0 }}"
                                    data-description="{{ $item->keterangan ?? 'Tidak ada deskripsi' }}"
                                    data-type="{{ $item->type->value }}"
                                    {{ (old('item_id', $incomingItem->item_id) == $item->id) ? 'selected' : '' }}>
                                {{ $item->nama }} - 
                                @if($item->type->value === 'stok')
                                    <span class="text-blue-600">[STOK]</span> (Reguler: {{ $item->stok_reguler }}) - {{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }} | {{ $item->category->name ?? 'N/A' }}
                                @else
                                    <span class="text-purple-600">[PEMINJAMAN]</span> (Peminjaman: {{ $item->stok_peminjaman }}) - {{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }} | {{ $item->category->name ?? 'N/A' }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div id="itemDetails" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-green-900 mb-3">Detail Barang</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-green-600 font-medium">Kode Barang:</span>
                            <span id="itemCode" class="ml-2 font-semibold text-green-900"></span>
                        </div>
                        <div>
                            <span class="text-green-600 font-medium">Supplier:</span>
                            <span id="supplierName" class="ml-2 font-semibold text-green-900"></span>
                        </div>
                        <div>
                            <span class="text-green-600 font-medium">Kategori:</span>
                            <span id="categoryName" class="ml-2 font-semibold text-green-900"></span>
                        </div>
                        <div>
                            <span class="text-green-600 font-medium" id="stockLabel">Stok Tersedia:</span>
                            <span id="availableStock" class="ml-2 font-semibold text-blue-600"></span>
                        </div>
                        <div id="priceSection">
                            <span class="text-green-600 font-medium">Harga Satuan:</span>
                            <span id="itemPrice" class="ml-2 font-semibold text-green-600"></span>
                        </div>
                        <div>
                            <span class="text-green-600 font-medium">Tipe Barang:</span>
                            <span class="ml-2">
                                <span id="itemTypeBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-green-200">
                        <div>
                            <span class="text-green-600 font-medium">Deskripsi:</span>
                            <p id="itemDescription" class="mt-1 text-sm text-green-800 italic"></p>
                        </div>
                    </div>
                </div>

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
                    <p class="mt-1 text-xs text-gray-500">
                        <strong>Catatan:</strong> Mengubah jumlah akan menyesuaikan stok barang secara otomatis.
                    </p>
                </div>

                <div id="stockImpact" class="hidden bg-yellow-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2">Dampak Perubahan Stok</h4>
                    <div class="text-sm text-yellow-700">
                        <div>Jumlah sebelumnya: <span id="oldQuantity" class="font-medium"></span></div>
                        <div>Jumlah baru: <span id="newQuantity" class="font-medium"></span></div>
                        <div>Perubahan stok: <span id="stockChange" class="font-medium"></span></div>
                    </div>
                </div>

                <div id="totalValue" class="hidden bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-green-700">Total Nilai Barang Masuk:</span>
                        <span id="totalAmount" class="text-lg font-bold text-green-900"></span>
                    </div>
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
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
                    <a href="{{ route('admin.incoming.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Barang Masuk
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const elements = {
        itemSelect: document.getElementById('item_id'),
        itemDetails: document.getElementById('itemDetails'),
        itemCode: document.getElementById('itemCode'),
        supplierName: document.getElementById('supplierName'),
        categoryName: document.getElementById('categoryName'),
        availableStock: document.getElementById('availableStock'),
        stockLabel: document.getElementById('stockLabel'),
        itemPrice: document.getElementById('itemPrice'),
        priceSection: document.getElementById('priceSection'),
        itemTypeBadge: document.getElementById('itemTypeBadge'),
        itemDescription: document.getElementById('itemDescription'),
        jumlahInput: document.getElementById('jumlah'),
        totalValue: document.getElementById('totalValue'),
        totalAmount: document.getElementById('totalAmount'),
        stockImpact: document.getElementById('stockImpact'),
        oldQuantity: document.getElementById('oldQuantity'),
        newQuantity: document.getElementById('newQuantity'),
        stockChange: document.getElementById('stockChange')
    };

    let currentPrice = 0;
    let currentType = '';
    const originalQuantity = {{ $incomingItem->jumlah }};

    function handleItemSelection() {
        const selectedOption = elements.itemSelect.options[elements.itemSelect.selectedIndex];
        
        if (selectedOption.value) {
            showItemDetails(selectedOption);
        } else {
            hideItemDetails();
        }
    }

    function showItemDetails(option) {
        elements.itemDetails.classList.remove('hidden');
        currentType = option.dataset.type;
        elements.itemCode.textContent = option.dataset.code || 'N/A';
        elements.supplierName.textContent = option.dataset.supplier || 'N/A';
        elements.categoryName.textContent = option.dataset.category || 'N/A';
        elements.itemDescription.textContent = option.dataset.description || 'Tidak ada deskripsi';
        if (currentType === 'stok') {
            const stockReguler = parseInt(option.dataset.stockReguler) || 0;
            elements.availableStock.textContent = stockReguler + ' unit';
            elements.stockLabel.textContent = 'Stok Tersedia:';
            elements.priceSection.style.display = 'block';
            currentPrice = parseInt(option.dataset.price) || 0;
            elements.itemPrice.textContent = currentPrice > 0 ? 'Rp ' + currentPrice.toLocaleString('id-ID') : 'Tidak ada harga';
            elements.itemTypeBadge.textContent = 'STOK';
            elements.itemTypeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            
        } else if (currentType === 'peminjaman') {
            const stockPeminjaman = parseInt(option.dataset.stockPeminjaman) || 0;
            elements.availableStock.textContent = stockPeminjaman + ' unit';
            elements.stockLabel.textContent = 'Stok Tersedia:';
            elements.priceSection.style.display = 'none';
            currentPrice = 0;
            elements.itemTypeBadge.textContent = 'PEMINJAMAN';
            elements.itemTypeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800';
        }
        calculateTotal();
        calculateStockImpact();
    }

    function hideItemDetails() {
        elements.itemDetails.classList.add('hidden');
        elements.totalValue.classList.add('hidden');
        elements.stockImpact.classList.add('hidden');
        currentPrice = 0;
        currentType = '';
    }
    elements.itemSelect.addEventListener('change', handleItemSelection);
    elements.jumlahInput.addEventListener('input', function() {
        calculateTotal();
        calculateStockImpact();
    });

    function calculateTotal() {
        const quantity = parseInt(elements.jumlahInput.value) || 0;
        
        if (currentPrice > 0 && quantity > 0) {
            const total = currentPrice * quantity;
            elements.totalAmount.textContent = 'Rp ' + total.toLocaleString('id-ID');
            elements.totalValue.classList.remove('hidden');
        } else {
            elements.totalValue.classList.add('hidden');
        }
    }

    function calculateStockImpact() {
        const newQty = parseInt(elements.jumlahInput.value) || 0;
        
        if (newQty !== originalQuantity) {
            const difference = newQty - originalQuantity;
            
            elements.oldQuantity.textContent = originalQuantity;
            elements.newQuantity.textContent = newQty;
            
            if (difference > 0) {
                elements.stockChange.textContent = '+' + difference + ' (Stok bertambah)';
                elements.stockChange.className = 'font-medium text-green-600';
            } else if (difference < 0) {
                elements.stockChange.textContent = difference + ' (Stok berkurang)';
                elements.stockChange.className = 'font-medium text-red-600';
            } else {
                elements.stockChange.textContent = '0 (Tidak ada perubahan)';
                elements.stockChange.className = 'font-medium text-gray-600';
            }
            
            elements.stockImpact.classList.remove('hidden');
        } else {
            elements.stockImpact.classList.add('hidden');
        }
    }
    if (elements.itemSelect.value) {
        handleItemSelection();
    }
});
</script>
@endsection
