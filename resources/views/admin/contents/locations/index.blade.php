@extends('admin.layouts.dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header Section --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="mb-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 sm:w-8 h-6 sm:h-8 text-blue-600 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Manajemen Lokasi
                </h1>
                <p class="text-gray-600 mt-1 text-sm">Kelola rak, lemari, dan ruangan penyimpanan barang</p>
            </div>
            <div>
                <a href="{{ route($routePrefix . '.locations.create') }}"
                   class="add-location-button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="hidden sm:inline">Tambah Lokasi</span>
                    <span class="sm:hidden">Tambah</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Lokasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalLocations }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Lokasi Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeLocations }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Nonaktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $inactiveLocations }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalItems }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Locations Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Daftar Lokasi
                    </h3>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <form method="GET" action="{{ route($routePrefix . '.locations.index') }}" class="flex items-center gap-2" id="locationSearchForm">
                        <input type="text"
                               id="searchInput"
                               name="search"
                               value="{{ $search }}"
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Cari nama, kode lokasi...">
                        <button type="button"
                                id="resetSearchBtn"
                                class="{{ $search ? '' : 'hidden' }} shrink-0 px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                title="Reset pencarian">
                            Reset
                        </button>
                    </form>
                    <a href="{{ route($routePrefix . '.locations.create') }}"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 whitespace-nowrap">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Quick Add
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">Berhasil! {{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Error! {{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lokasi</th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Lokasi Induk</th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($locations as $location)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-9 w-9">
                                    <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $location->name }}</div>
                                    @if($location->deskripsi)
                                        <div class="text-xs text-gray-500">{{ Str::limit($location->deskripsi, 50) }}</div>
                                    @else
                                        <div class="text-xs text-gray-400">ID: {{ $location->id }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                            @if($location->kode)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 font-mono">
                                    {{ $location->kode }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap hidden md:table-cell">
                            @if($location->parent)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $location->parent->name }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">Lokasi Utama</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $location->items_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-500' }}">
                                {{ $location->items_count }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-center">
                            @if($location->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-1">
                                <a href="{{ route($routePrefix . '.locations.show', $location) }}"
                                   class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-1.5 rounded-md transition-colors duration-200"
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route($routePrefix . '.locations.edit', $location) }}"
                                   class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-1.5 rounded-md transition-colors duration-200"
                                   title="Edit Lokasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button type="button"
                                        onclick="openModal('del-loc-{{ $location->id }}')"
                                        class="delete-location-button"
                                        title="Hapus Lokasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                @if($search)
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada hasil</h3>
                                    <p class="mt-1 text-sm text-gray-500">Tidak ada lokasi yang cocok dengan "{{ $search }}".</p>
                                    <div class="mt-6">
                                        <a href="{{ route($routePrefix . '.locations.index') }}"
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Reset Pencarian
                                        </a>
                                    </div>
                                @else
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada lokasi</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat lokasi pertama Anda.</p>
                                    <div class="mt-6">
                                        <a href="{{ route($routePrefix . '.locations.create') }}"
                                           class="create-first-location-button">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Buat Lokasi Pertama
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($locations->hasPages())
    <div class="bg-white px-4 py-3 border border-gray-200 rounded-lg shadow-sm">
        {{ $locations->appends(request()->query())->links() }}
    </div>
    @endif

</div>

<style>
    .delete-location-button {
        display: inline-flex !important;
        color: #dc2626 !important;
        background-color: #fef2f2 !important;
        border: none !important;
        padding: 0.375rem !important;
        border-radius: 0.375rem !important;
        transition: all 0.2s ease-in-out !important;
        cursor: pointer !important;
    }
    .delete-location-button:hover {
        color: #991b1b !important;
        background-color: #fee2e2 !important;
        transform: translateY(-1px) !important;
    }
    .create-first-location-button, .add-location-button {
        display: inline-flex !important;
        align-items: center !important;
        background-color: #2563eb !important;
        color: white !important;
        border: none !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0.375rem !important;
        font-weight: 500 !important;
        font-size: 0.875rem !important;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06) !important;
        transition: all 0.2s ease-in-out !important;
        text-decoration: none !important;
    }
    .create-first-location-button:hover, .add-location-button:hover {
        background-color: #1d4ed8 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05) !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('locationSearchForm');
    const input = document.getElementById('searchInput');
    const resetBtn = document.getElementById('resetSearchBtn');

    if (!form || !input) return;

    let timeoutId;

    const updateResetBtnVisibility = () => {
        if (!resetBtn) return;
        resetBtn.classList.toggle('hidden', input.value.trim() === '');
    };

    input.addEventListener('input', function () {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function () {
            const keyword = input.value.trim();
            if (keyword === '' || keyword.length >= 2) form.submit();
        }, 700);
        updateResetBtnVisibility();
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            input.value = '';
            updateResetBtnVisibility();
            form.submit();
        });
    }

    updateResetBtnVisibility();
});
</script>

@push('scripts')
    @foreach($locations as $location)
        <x-popup id="del-loc-{{ $location->id }}" title="Hapus Lokasi"
            message="Apakah Anda yakin ingin menghapus lokasi '{{ $location->name }}'? Pastikan tidak ada barang yang masih berada di lokasi ini."
            formId="del-loc-form-{{ $location->id }}"
            confirmText="Hapus"
            confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
            cancelText="Batal" />
        <form id="del-loc-form-{{ $location->id }}" action="{{ route($routePrefix . '.locations.destroy', $location) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endpush

@endsection
