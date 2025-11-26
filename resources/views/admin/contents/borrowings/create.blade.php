@extends('admin.layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Peminjaman Barang</h1>
                    <p class="text-sm text-gray-600 mt-1">Catat peminjaman barang baru</p>
                </div>
                <a href="{{ route('admin.borrowings.index') }}" 
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
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Form Peminjaman</h3>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('admin.borrowings.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="item_id" class="block text-sm font-medium text-gray-700 mb-2">Barang <span class="text-red-500">*</span></label>
                        <select name="item_id" id="item_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('item_id') border-red-300 @enderror">
                            <option value="">Pilih Barang</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" 
                                        data-stock="{{ $item->stok_peminjaman }}"
                                        data-code="{{ $item->id ? 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}"
                                        data-supplier="{{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }}"
                                        data-category="{{ $item->category->name ?? 'N/A' }}"
                                        data-description="{{ $item->keterangan ?? 'Tidak ada deskripsi' }}"
                                        {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }} - {{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }} (Stok: {{ $item->stok_peminjaman }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="stock-info" class="mt-1 text-sm text-gray-500 hidden">
                            Stok tersedia: <span id="available-stock">0</span>
                        </div>
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Peminjam <span class="text-red-500">*</span></label>
                        <select name="user_id" id="user_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('user_id') border-red-300 @enderror">
                            <option value="">Pilih Peminjam</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah', 0) }}" required 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('jumlah') border-red-300 @enderror">
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tanggal_pinjam') border-red-300 @enderror">
                        @error('tanggal_pinjam')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Rencana Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}" required 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tanggal_kembali_rencana') border-red-300 @enderror">
                        @error('tanggal_kembali_rencana')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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

                <div id="itemDetails" class="hidden bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-purple-900 mb-3">Detail Barang</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-purple-600 font-medium">Kode Barang:</span>
                            <span id="itemCode" class="ml-2 font-semibold text-purple-900"></span>
                        </div>
                        <div>
                            <span class="text-purple-600 font-medium">Supplier:</span>
                            <span id="supplierName" class="ml-2 font-semibold text-purple-900"></span>
                        </div>
                        <div>
                            <span class="text-purple-600 font-medium">Kategori:</span>
                            <span id="categoryName" class="ml-2 font-semibold text-purple-900"></span>
                        </div>
                        <div>
                            <span class="text-purple-600 font-medium">Stok Tersedia:</span>
                            <span id="availableStockDetail" class="ml-2 font-semibold text-green-600"></span>
                        </div>
                        <div>
                            <span class="text-purple-600 font-medium">Tipe Barang:</span>
                            <span class="ml-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    PEMINJAMAN
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-purple-200">
                        <div>
                            <span class="text-purple-600 font-medium">Deskripsi:</span>
                            <p id="itemDescription" class="mt-1 text-sm text-purple-800 italic"></p>
                        </div>
                    </div>
                </div>

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
                    <a href="{{ route('admin.borrowings.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
document.addEventListener('DOMContentLoaded', function() {
    const elements = {
        itemSelect: document.getElementById('item_id'),
        stockInfo: document.getElementById('stock-info'),
        availableStock: document.getElementById('available-stock'),
        quantityInput: document.getElementById('jumlah'),
        itemDetails: document.getElementById('itemDetails'),
        itemCode: document.getElementById('itemCode'),
        supplierName: document.getElementById('supplierName'),
        categoryName: document.getElementById('categoryName'),
        availableStockDetail: document.getElementById('availableStockDetail'),
        itemDescription: document.getElementById('itemDescription'),
        borrowDate: document.getElementById('tanggal_pinjam'),
        returnDate: document.getElementById('tanggal_kembali_rencana')
    };

    function handleItemSelection() {
        const selectedOption = elements.itemSelect.options[elements.itemSelect.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');
        
        if (selectedOption.value && stock) {
            showItemDetails(selectedOption, stock);
        } else {
            hideItemDetails();
        }
    }

    function showItemDetails(option, stock) {
        elements.availableStock.textContent = stock;
        elements.stockInfo.classList.remove('hidden');
        elements.quantityInput.setAttribute('max', stock);
    
        elements.itemDetails.classList.remove('hidden');
        elements.itemCode.textContent = option.getAttribute('data-code') || 'N/A';
        elements.supplierName.textContent = option.getAttribute('data-supplier') || 'N/A';
        elements.categoryName.textContent = option.getAttribute('data-category') || 'N/A';
        elements.availableStockDetail.textContent = stock + ' unit';
        elements.itemDescription.textContent = option.getAttribute('data-description') || 'Tidak ada deskripsi';
    }

    function hideItemDetails() {
        elements.stockInfo.classList.add('hidden');
        elements.itemDetails.classList.add('hidden');
        elements.quantityInput.removeAttribute('max');
    }

    function handleDateValidation() {
        const borrowDateValue = new Date(elements.borrowDate.value);
        borrowDateValue.setDate(borrowDateValue.getDate() + 1);
        const minReturnDate = borrowDateValue.toISOString().split('T')[0];
        
        elements.returnDate.setAttribute('min', minReturnDate);
        
        if (elements.returnDate.value && elements.returnDate.value <= elements.borrowDate.value) {
            elements.returnDate.value = minReturnDate;
        }
    }

    elements.itemSelect.addEventListener('change', handleItemSelection);
    elements.borrowDate.addEventListener('change', handleDateValidation);

    if (elements.itemSelect.value) {
        handleItemSelection();
    }
});
</script>
@endsection
