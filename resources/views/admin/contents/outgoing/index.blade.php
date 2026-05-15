@extends('admin.layouts.dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Barang Keluar</h1>
                        <p class="text-sm text-gray-600 mt-1">Kelola data barang yang keluar dari inventory</p>
                    </div>
                    <a href="{{ route($routePrefix . '.outgoing.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 whitespace-nowrap">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="hidden sm:inline">Tambah Barang Keluar</span>
                        <span class="sm:hidden">Tambah</span>
                    </a>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_items'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Quantity</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_quantity']) }}
                                </p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        {{-- Clean Filter Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Filter & Pencarian</h3>
                <p class="text-sm text-gray-600">Filter data barang keluar berdasarkan kriteria di bawah</p>
            </div>

            <form method="GET" action="{{ route($routePrefix . '.outgoing.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    {{-- Search Input --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Pencarian Barang</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Cari nama barang...">
                    </div>

                    {{-- User Filter --}}
                    <div>
                        <label for="user_filter" class="block text-sm font-medium text-gray-700 mb-2">Filter User</label>
                        <select name="user_filter" id="user_filter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Semua User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <a href="{{ route($routePrefix . '.outgoing.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200">
                        Reset Filters
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            @if($outgoingItems->count() > 0)
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Barang Keluar</h3>
                        <div class="flex items-center space-x-2">
                            <button type="button" id="selectAll" class="text-sm text-blue-600 hover:text-blue-800">Select
                                All</button>
                            <button type="button" id="bulkDelete" class="text-sm text-red-600 hover:text-red-800"
                                style="display: none;">Delete Selected</button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAllCheckbox"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                                    Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Diambil Oleh</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                                    Pengambil</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($outgoingItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                            class="item-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->item)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->item?->nama ?? 'Item Dihapus' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->user?->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->user?->id ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->item?->supplier?->nama ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->item?->category?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->jumlah }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                                            @if($item->status === 'sold') bg-green-100 text-green-800
                                                                            @elseif($item->status === 'damaged') bg-red-100 text-red-800
                                                                            @elseif($item->status === 'expired') bg-orange-100 text-orange-800
                                                                            @elseif($item->status === 'lost') bg-yellow-100 text-yellow-800
                                                                            @elseif($item->status === 'to_production') bg-blue-100 text-blue-800
                                                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($item->status === 'to_production')
                                                To Production
                                            @else
                                                {{ ucfirst($item->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route($routePrefix . '.outgoing.show', $item->id) }}"
                                                class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route($routePrefix . '.outgoing.edit', $item->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <button type="button" onclick="openModal('del-out-{{ $item->id }}')" class="text-red-600 hover:text-red-900">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $outgoingItems->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada barang keluar</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan barang keluar baru.</p>
                    <div class="mt-6">
                        <a href="{{ route($routePrefix . '.outgoing.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Barang Keluar
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Bulk Delete Form --}}
    <form id="bulkDeleteForm" action="{{ route($routePrefix . '.outgoing.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <div id="selectedItemsContainer"></div>
    </form>


    @push('styles')
        <style>
            .pagination {
                display: flex;
                justify-content: center;
                margin-top: 1.5rem;
            }

            .pagination>* {
                margin: 0 0.25rem;
                padding: 0.5rem 0.75rem;
                border: 1px solid #e2e8f0;
                border-radius: 0.375rem;
                color: #4a5568;
                text-decoration: none;
                transition: all 0.2s;
            }

            .pagination>*:hover:not(.disabled) {
                background-color: #f7fafc;
            }

            .pagination .active {
                background-color: #48bb78;
                color: white;
                border-color: #48bb78;
            }

            .pagination .disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
        </style>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const bulkDeleteBtn = document.getElementById('bulkDelete');
            const selectAllBtn = document.getElementById('selectAll');

            // Select all functionality
            selectAllCheckbox.addEventListener('change', function () {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkActions();
            });

            selectAllBtn.addEventListener('click', function () {
                const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
                selectAllCheckbox.checked = !allChecked;
                toggleBulkActions();
            });

            // Individual checkbox change
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                    selectAllCheckbox.checked = checkedCount === itemCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < itemCheckboxes.length;
                    toggleBulkActions();
                });
            });

            // Toggle bulk actions visibility
            function toggleBulkActions() {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                bulkDeleteBtn.style.display = checkedCount > 0 ? 'inline' : 'none';
            }

            // Bulk delete
            bulkDeleteBtn.addEventListener('click', function () {
                const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    alert('Please select items to delete');
                    return;
                }

                const container = document.getElementById('selectedItemsContainer');
                container.innerHTML = '';
                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_items[]';
                    input.value = checkbox.value;
                    container.appendChild(input);
                });

                openModal('bulk-del-modal');
            });
        });
    </script>
@endsection

@push('scripts')
    <x-popup id="bulk-del-modal" title="Hapus Item Terpilih"
        message="Are you sure you want to delete selected items?"
        formId="bulkDeleteForm"
        confirmText="Hapus"
        confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal" />

    @foreach($outgoingItems as $item)
        <x-popup id="del-out-{{ $item->id }}" title="Hapus Barang Keluar"
            message="Are you sure you want to delete this outgoing item?"
            formId="del-out-form-{{ $item->id }}"
            confirmText="Hapus"
            confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
            cancelText="Batal" />
        <form id="del-out-form-{{ $item->id }}" action="{{ route($routePrefix . '.outgoing.destroy', $item->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endpush