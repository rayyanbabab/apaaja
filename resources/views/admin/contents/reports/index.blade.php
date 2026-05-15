@extends('admin.layouts.dashboard')

@section('content')
<div class="p-6">
    {{-- Simple Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">📊 Business Reports</h1>
        <p class="text-gray-600">Generate comprehensive business insights and analytics</p>
    </div>

    {{-- Report Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Inventory Report --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600">{{ App\Models\Item::count() }}</div>
                    <div class="text-xs text-gray-500">TOTAL ITEMS</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">📦 Inventory Report</h3>
            <p class="text-gray-600 text-sm mb-4">Complete stock & item details</p>
            <a href="{{ route($routePrefix . '.reports.inventory') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-medium transition-colors">
                Generate Report
            </a>
        </div>

        {{-- Outgoing Items Report --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-red-600">{{ App\Models\Inventory::where('tipe', 'keluar')->count() }}</div>
                    <div class="text-xs text-gray-500">ITEMS OUT</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">📤 Outgoing Items</h3>
            <p class="text-gray-600 text-sm mb-4">Items sent to production & usage</p>
            <a href="{{ route($routePrefix . '.reports.outgoing') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white text-sm font-medium transition-colors">
                Generate Report
            </a>
        </div>

        {{-- Incoming Items Report --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600">{{ App\Models\Inventory::where('tipe', 'masuk')->count() }}</div>
                    <div class="text-xs text-gray-500">ITEMS IN</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">📥 Incoming Items</h3>
            <p class="text-gray-600 text-sm mb-4">Items received from suppliers</p>
            <a href="{{ route($routePrefix . '.reports.incoming') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-white text-sm font-medium transition-colors">
                Generate Report
            </a>
        </div>

        {{-- Suppliers Report --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-amber-600">{{ App\Models\Supplier::count() }}</div>
                    <div class="text-xs text-gray-500">SUPPLIERS</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">🏢 Suppliers Report</h3>
            <p class="text-gray-600 text-sm mb-4">Complete supplier database</p>
            <a href="{{ route($routePrefix . '.reports.suppliers') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-medium transition-colors">
                Generate Report
            </a>
        </div>

        {{-- Borrowing Report --}}
        <div class="bg-white rounded-lg shadow-md border border-purple-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-purple-600">{{ App\Models\BorrowingRequest::count() }}</div>
                    <div class="text-xs text-gray-500">TOTAL PINJAMAN</div>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">📋 Borrowing Report</h3>
            <p class="text-gray-600 text-sm mb-4">Riwayat & status peminjaman barang</p>
            <a href="{{ route($routePrefix . '.reports.borrowing') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
                Generate Report
            </a>
        </div>

    </div>
</div>
@endsection
