@extends('user.layouts.dashboard-user')

@section('title', 'Submit Item Borrowing Request')

@section('user')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('user.borrowing.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Submit Item Borrowing Request</h1>
                <p class="text-gray-600 mt-2">Complete the form below to submit a borrowing request</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Item Information Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Item Details -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Item Name</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $item->nama }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Available Stock</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $item->stok_peminjaman }} unit
                            </span>
                        </div>
                        @if($item->keterangan)
                        <div class="pt-3 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-500 block mb-1">Description</span>
                            <p class="text-sm text-gray-700">{{ $item->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowing Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Borrowing Form</h3>
                    <p class="text-sm text-gray-600 mt-1">Fill in the borrowing data completely and correctly</p>
                </div>
                
                <form action="{{ route('user.borrowing.store') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
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

                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jumlah Pinjam -->
                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity to Borrow <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jumlah') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   id="jumlah" 
                                   name="jumlah" 
                                   min="1" 
                                   max="{{ $item->stok_peminjaman }}"
                                   value="{{ old('jumlah') }}" 
                                   placeholder="Enter quantity"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Maximum: {{ $item->stok_peminjaman }} units</p>
                            @error('jumlah')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Pinjam -->
                        <div>
                            <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                                Borrow Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_pinjam') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   id="tanggal_pinjam" 
                                   name="tanggal_pinjam" 
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('tanggal_pinjam') }}" 
                                   required>
                            @error('tanggal_pinjam')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Kembali -->
                        <div class="md:col-span-2">
                            <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700 mb-2">
                                Planned Return Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_kembali_rencana') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   id="tanggal_kembali_rencana" 
                                   name="tanggal_kembali_rencana" 
                                   value="{{ old('tanggal_kembali_rencana') }}" 
                                   required>
                            <p class="text-xs text-gray-400 mt-1">
                                💡 Batas peminjaman maksimal <strong>{{ $maxBorrowDays }} hari</strong> dari tanggal pinjam.
                            </p>
                            @error('tanggal_kembali_rencana')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mt-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes/Purpose of Borrowing
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                  id="keterangan" 
                                  name="keterangan" 
                                  rows="3" 
                                  placeholder="Explain the purpose or need for borrowing this item...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kondisi Barang -->
                    <div class="mt-6">
                        <label for="kondisi_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                            Item Condition When Borrowed
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kondisi_pinjam') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                  id="kondisi_pinjam" 
                                  name="kondisi_pinjam" 
                                  rows="2" 
                                  placeholder="Record the condition of the item when borrowed (optional)">{{ old('kondisi_pinjam') }}</textarea>
                        @error('kondisi_pinjam')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('user.borrowing.index') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back
                        </a>
                        <button type="submit"
                                style="background:#2563EB;color:#fff;border:none;cursor:pointer;"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm"
                                onmouseover="this.style.background='#1D4ED8'" onmouseout="this.style.background='#2563EB'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Submit Borrowing Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
    const maxBorrowDays = {{ $maxBorrowDays }};

    function updateKembali() {
        if (!tanggalPinjam.value) return;
        const pinjamDate = new Date(tanggalPinjam.value);
        const nextDay = new Date(pinjamDate);
        nextDay.setDate(nextDay.getDate() + 1);
        tanggalKembali.min = nextDay.toISOString().split('T')[0];

        // Auto-set return date = pinjam + maxBorrowDays (if not already set)
        if (!tanggalKembali.value || !document.getElementById('tanggal_kembali_rencana').dataset.userEdited) {
            const defaultReturn = new Date(pinjamDate);
            defaultReturn.setDate(defaultReturn.getDate() + maxBorrowDays);
            tanggalKembali.value = defaultReturn.toISOString().split('T')[0];
        }

        // Also enforce max date
        const maxDate = new Date(pinjamDate);
        maxDate.setDate(maxDate.getDate() + maxBorrowDays);
        tanggalKembali.max = maxDate.toISOString().split('T')[0];

        if (tanggalKembali.value && new Date(tanggalKembali.value) <= pinjamDate) {
            tanggalKembali.value = '';
        }
    }

    tanggalPinjam.addEventListener('change', updateKembali);
    tanggalKembali.addEventListener('change', function() {
        this.dataset.userEdited = '1';
    });

    // Set borrow date to today by default
    if (!tanggalPinjam.value) {
        tanggalPinjam.value = new Date().toISOString().split('T')[0];
        updateKembali();
    }
});
</script>
@endpush