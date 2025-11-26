@extends('admin.layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Peminjaman</h1>
                    <p class="text-sm text-gray-600 mt-1">Update data peminjaman barang</p>
                </div>
                <a href="{{ route('admin.borrowings.show', $borrowing) }}" 
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
            <h3 class="text-lg font-medium text-gray-900">Form Edit Peminjaman</h3>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('admin.borrowings.update', $borrowing) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Peminjaman</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Barang:</span>
                            <span class="font-medium text-gray-900">{{ $borrowing->item->nama }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Peminjam:</span>
                            <span class="font-medium text-gray-900">{{ $borrowing->user->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Jumlah:</span>
                            <span class="font-medium text-gray-900">{{ $borrowing->jumlah }} unit</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Tanggal Pinjam:</span>
                            <span class="font-medium text-gray-900">{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Rencana Kembali:</span>
                            <span class="font-medium text-gray-900">{{ $borrowing->tanggal_kembali_rencana->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Status Saat Ini:</span>
                            @if($borrowing->status === 'dipinjam')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Dipinjam</span>
                            @elseif($borrowing->status === 'dikembalikan')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Dikembalikan</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('status') border-red-300 @enderror">
                            <option value="dipinjam" {{ old('status', $borrowing->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ old('status', $borrowing->status) == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="terlambat" {{ old('status', $borrowing->status) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_kembali_aktual" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kembali Aktual</label>
                        <input type="date" name="tanggal_kembali_aktual" id="tanggal_kembali_aktual" 
                               value="{{ old('tanggal_kembali_aktual', $borrowing->tanggal_kembali_aktual?->format('Y-m-d')) }}" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tanggal_kembali_aktual') border-red-300 @enderror">
                        @error('tanggal_kembali_aktual')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika belum dikembalikan</p>
                    </div>

                    <div>
                        <label for="kondisi_kembali" class="block text-sm font-medium text-gray-700 mb-2">Kondisi Saat Dikembalikan</label>
                        <textarea name="kondisi_kembali" id="kondisi_kembali" rows="3" 
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('kondisi_kembali') border-red-300 @enderror"
                                  placeholder="Contoh: Baik, tidak ada kerusakan / Ada sedikit goresan">{{ old('kondisi_kembali', $borrowing->kondisi_kembali) }}</textarea>
                        @error('kondisi_kembali')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" 
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('keterangan') border-red-300 @enderror"
                                  placeholder="Catatan tambahan">{{ old('keterangan', $borrowing->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($borrowing->kondisi_pinjam)
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Kondisi Saat Dipinjam</h4>
                        <p class="text-sm text-blue-800">{{ $borrowing->kondisi_pinjam }}</p>
                    </div>
                @endif

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.borrowings.show', $borrowing) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const returnDateInput = document.getElementById('tanggal_kembali_aktual');
    const returnConditionTextarea = document.getElementById('kondisi_kembali');

    statusSelect.addEventListener('change', function() {
        if (this.value === 'dikembalikan') {
            if (!returnDateInput.value) {
                returnDateInput.value = new Date().toISOString().split('T')[0];
            }
            returnDateInput.required = true;
            returnConditionTextarea.required = true;
        } else {
            returnDateInput.required = false;
            returnConditionTextarea.required = false;
        }
    });

    statusSelect.dispatchEvent(new Event('change'));
});
</script>
@endsection
