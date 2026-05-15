@extends('admin.layouts.dashboard')

@section('content')
    <x-inventory-form>
        <x-heading-section title="Details items" title-color="text-base text-gray-700" subtitle="Manage items, see the detail information" subtitle-color="text-xs text-gray-400" />

        <div class="mb-6 bg-white rounded-lg border border-gray-200 shadow-sm p-4">
            <form method="GET" action="{{ route($routePrefix . '.inventory.tab.detail') }}" class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Filter Items</h3>
                    <button type="button" onclick="resetFilters()" class="text-xs text-gray-500 hover:text-gray-700">
                        Reset All
                    </button>
                </div>
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search items..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label for="type" class="block text-xs font-medium text-gray-700 mb-1">Tipe Barang</label>
                        <select id="type" name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="stok" {{ request('type') == 'stok' ? 'selected' : '' }}>Stok</option>
                            <option value="peminjaman" {{ request('type') == 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                        </select>
                    </div>

                    <div>
                        <label for="category_id" class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="category_id" name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="supplier_id" class="block text-xs font-medium text-gray-700 mb-1">Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Semua Supplier</option>
                            @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="text-xs text-gray-500">
                        Showing {{ $items->count() }} items
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div x-data="{ activeTab: 'list-items' }" class="mt-4">
            <div x-show="activeTab === 'list-items'" x-transition>
                @if ($items->isEmpty())
                    <x-empty-states />
                @else
                    {{-- Grid List Item --}}
                    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
                        <table class="table-next">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="th-next">#</th>
                                    <th class="th-next">Name</th>
                                    <th class="th-next">Kode Barang</th>
                                    <th class="th-next">Tipe</th>
                                    <th class="th-next">Category</th>
                                    <th class="th-next">Stock</th>
                                    <th class="th-next">Supplier</th>
                                    <th class="th-next">Created</th>
                                    <th class="th-next">Updated</th>
                                    <th class="th-next">Description</th>
                                    <th class="th-next text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($items as $index => $item)
                                    @php
                                        $currentItemTypeVal = $item->type?->value ?? $item->getRawOriginal('type') ?? 'stok';
                                        $isCurrentItemBorrowing = $currentItemTypeVal === 'peminjaman';
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="td-next">{{ $index + 1 }}</td>
                                        <td class="flex items-center space-x-2 td-next">
                                            @php
                                                $displayImage = $item->gambar;
                                            @endphp
                                            
                                            @if ($displayImage)
                                                <img class="size-10 rounded-full object-cover" src="{{ asset($displayImage) }}"
                                                    alt="{{ $item->nama }}">
                                            @else
                                                <div class="bg-gray-200 text-gray-400 rounded-full p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" stroke-width="2"
                                                        class="lucide lucide-package-icon lucide-package size-6"
                                                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor">
                                                        <path
                                                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                                                        <path d="M12 22V12" />
                                                        <polyline points="3.29 7 12 12 20.71 7" />
                                                        <path d="m7.5 4.27 9 5.15" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="flex flex-col">
                                                <div class="flex">
                                                    <span class="font-semibold text-slate-900">{{ $item->nama }}</span>
                                                </div>
                                                @if(!$isCurrentItemBorrowing)
                                                    <span class="text-sm text-yellow-500">
                                                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="td-next text-sm text-slate-700">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $item->id ? 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="td-next text-sm text-slate-700">
                                            @if($isCurrentItemBorrowing)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                    </svg>
                                                    Peminjaman
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                    Stok
                                                </span>
                                            @endif
                                        </td>
                                        <td class="td-next text-sm text-slate-700">
                                            @if($item->category)
                                                @if($isCurrentItemBorrowing)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        {{ $item->category->name }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $item->category->name }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="td-next text-sm text-slate-700">
                                            <div class="flex flex-col space-y-1">
                                                @if($isCurrentItemBorrowing)
                                                    <span class="text-sm font-medium text-blue-600">
                                                        Peminjaman: {{ $item->stok_peminjaman ?? 0 }}
                                                    </span>
                                                @else
                                                    <span class="text-sm font-medium text-green-600">
                                                        Reguler: {{ $item->stok_reguler ?? 0 }}
                                                    </span>
                                                @endif
                                                
                                                <span class="text-xs text-gray-500">
                                                    Total: {{ $item->stok_total ?? $item->stok ?? 0 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="td-next font-semibold text-gray-500">
                                            @if($item->supplier)
                                                @if($isCurrentItemBorrowing)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        {{ $item->supplier->company_name }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $item->supplier->company_name }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="font-semibold text-sm text-yellow-400">{{ $item->created_at->format(' d-F-y') }}
                                        </td>
                                        <td class="font-semibold text-sm text-blue-400">{{ $item->updated_at->format('d-F-y') }}
                                        </td>
                                        <td class="td-next">{{ $item->keterangan ?? '-' }}</td>
                                        <td class="td-next">
                                            <div class="flex w-full items-center justify-center gap-2">
                                               

                                                <a href="{{ route($routePrefix . '.inventory.show', $item->id) }}"
                                                    class="rounded-md border border-green-500 px-2 py-1 text-sm text-green-600 hover:bg-green-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>

                                                <a href="{{ route($routePrefix . '.inventory.edit', $item->id) }}"
                                                    class="rounded-md border border-blue-500 px-2 py-1 text-sm text-blue-600 hover:bg-blue-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>

                                                <button type="button"
                                                    onclick="openModal('delete-modal-{{ $item->id }}')"
                                                    class="rounded-md border border-red-500 px-2 py-1 text-sm text-red-600 hover:bg-red-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                            </div>

                                            <form id="delete-form-{{ $item->id }}" method="POST"
                                                action="{{ route($routePrefix . '.inventory.destroy', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <x-popup id="delete-modal-{{ $item->id }}" title="Delete Item"
                                                message="Are you sure you want to delete '{{ $item->nama }}'? This action cannot be undone and all data will be permanently removed."
                                                :formId="'delete-form-' . $item->id" confirmText="Delete"
                                                confirmClass="link-primary delete-btn-color" cancelText="Cancel" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-4 py-6 text-center text-slate-500">Tidak ada data
                                            barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (method_exists($items, 'links'))
                        <div class="mt-8">
                            {{ $items->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </x-inventory-form>

    <script>
        function resetFilters() {
            document.getElementById('search').value = '';
            document.getElementById('type').value = '';
            document.getElementById('category_id').value = '';
            document.getElementById('supplier_id').value = '';
            
            window.location.href = '{{ route("admin.inventory.tab.detail") }}';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const filterInputs = ['type', 'category_id', 'supplier_id'];
            
            filterInputs.forEach(function(inputId) {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('change', function() {
                        this.form.submit();
                    });
                }
            });

            const searchInput = document.getElementById('search');
            if (searchInput) {
                let timeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        this.form.submit();
                    }, 500); 
                });
            }
        });
    </script>
@endsection
