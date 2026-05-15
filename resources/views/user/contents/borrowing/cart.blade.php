@extends('user.layouts.dashboard-user')

@section('title', 'Borrowing Cart')

@section('user')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Borrowing Cart</h1>
                <p class="text-gray-600 mt-2">Manage items you will borrow</p>
            </div>
            <a href="{{ route('user.borrowing.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Add Items</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Cart Items ({{ $cartItems->count() }})</h3>
                        <form id="empty-cart-form" action="{{ route('user.borrowing.cart.clear') }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="openModal('modal-empty-cart')">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Empty Cart
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($cartItems as $cartItem)
                        <div class="flex gap-4 p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <!-- Image -->
                            <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                @if($cartItem->item->gambar)
                                    <img src="{{ asset($cartItem->item->gambar) }}" alt="{{ $cartItem->item->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $cartItem->item->nama }}</h4>
                                <p class="text-sm text-gray-600 mb-2">
                                    Available stock: <span class="font-medium text-green-600">{{ $cartItem->item->stok_peminjaman }} units</span>
                                </p>
                                
                                <!-- Quantity Control -->
                                <form action="{{ route('user.borrowing.cart.update', $cartItem->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    <label class="text-sm text-gray-600">Quantity:</label>
                                    <input type="number" name="jumlah" value="{{ $cartItem->jumlah }}" min="1" max="{{ $cartItem->item->stok_peminjaman }}" 
                                           class="w-20 px-2 py-1 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Update
                                    </button>
                                </form>
                            </div>

                            <!-- Remove Button -->
                            <div class="flex-shrink-0">
                                <form id="remove-cart-item-{{ $cartItem->id }}" action="{{ route('user.borrowing.cart.remove', $cartItem->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="text-red-600 hover:text-red-800 p-2" onclick="openModal('modal-remove-item-{{ $cartItem->id }}')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-4">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-semibold text-gray-900">Borrowing Details</h3>
                </div>
                <form action="{{ route('user.borrowing.cart.checkout') }}" method="POST" class="p-6">
                    @csrf
                    
                    <!-- Tanggal Pinjam -->
                    <div class="mb-4">
                        <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                            Borrow Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="tanggal_pinjam" 
                               name="tanggal_pinjam" 
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                               required>
                    </div>

                    <!-- Tanggal Kembali -->
                    <div class="mb-4">
                        <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700 mb-2">
                            Planned Return Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="tanggal_kembali_rencana" 
                               name="tanggal_kembali_rencana" 
                               value="{{ old('tanggal_kembali_rencana') }}" 
                               required>
                        <p class="text-xs text-gray-400 mt-1">
                            💡 Batas peminjaman maksimal <strong>{{ $maxBorrowDays }} hari</strong> dari tanggal pinjam.
                        </p>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-4">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes/Purpose of Borrowing
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  id="keterangan" 
                                  name="keterangan" 
                                  rows="3" 
                                  placeholder="Explain the purpose of borrowing...">{{ old('keterangan') }}</textarea>
                    </div>

                    <!-- Kondisi Barang -->
                    <div class="mb-6">
                        <label for="kondisi_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                            Item Condition When Borrowed
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  id="kondisi_pinjam" 
                                  name="kondisi_pinjam" 
                                  rows="2" 
                                  placeholder="Record item condition...">{{ old('kondisi_pinjam') }}</textarea>
                    </div>

                    <!-- Summary -->
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Total Items:</span>
                            <span class="font-semibold text-gray-900">{{ $cartItems->count() }} item</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total Quantity:</span>
                            <span class="font-semibold text-gray-900">{{ $cartItems->sum('jumlah') }} units</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            style="background:#2563EB;color:#fff;border:none;cursor:pointer;width:100%;"
                            class="py-3 px-4 rounded-lg font-semibold flex items-center justify-center gap-2"
                            onmouseover="this.style.background='#1D4ED8'" onmouseout="this.style.background='#2563EB'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Submit All Borrowing Requests
                    </button>
                </form>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-16">
        <div class="text-center">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Empty Cart</h3>
            <p class="text-gray-600 mb-6">You haven't added any items to the borrowing cart.</p>
            <a href="{{ route('user.borrowing.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Find Items to Borrow
            </a>
        </div>
    </div>
    @endif
</div>

@push('scripts')
@if($cartItems->count() > 0)
    <x-popup id="modal-empty-cart" title="Empty Cart"
        message="Are you sure you want to empty the cart? All items will be removed."
        formId="empty-cart-form"
        confirmText="Empty Cart"
        confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
        cancelText="Cancel" />
    
    @foreach($cartItems as $cartItem)
        <x-popup id="modal-remove-item-{{ $cartItem->id }}" title="Remove Item"
            message="Remove this item from cart?"
            formId="remove-cart-item-{{ $cartItem->id }}"
            confirmText="Remove"
            confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
            cancelText="Cancel" />
    @endforeach
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalPinjam  = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
    const maxBorrowDays  = {{ $maxBorrowDays ?? 7 }};

    function updateReturnDate() {
        if (!tanggalPinjam || !tanggalKembali || !tanggalPinjam.value) return;
        const pinjam  = new Date(tanggalPinjam.value);

        // Min = pinjam + 1 day
        const minDate = new Date(pinjam);
        minDate.setDate(minDate.getDate() + 1);
        tanggalKembali.min = minDate.toISOString().split('T')[0];

        // Max = pinjam + maxBorrowDays
        const maxDate = new Date(pinjam);
        maxDate.setDate(maxDate.getDate() + maxBorrowDays);
        tanggalKembali.max = maxDate.toISOString().split('T')[0];

        // Auto-set to max if not user-edited or out of range
        if (!tanggalKembali.dataset.userEdited || !tanggalKembali.value || new Date(tanggalKembali.value) <= pinjam) {
            tanggalKembali.value = maxDate.toISOString().split('T')[0];
        }
    }

    if (tanggalPinjam && tanggalKembali) {
        tanggalPinjam.addEventListener('change', updateReturnDate);
        tanggalKembali.addEventListener('change', function() {
            this.dataset.userEdited = '1';
        });
        // Run on page load to auto-fill return date
        updateReturnDate();
    }
});
</script>
@endpush
@endsection