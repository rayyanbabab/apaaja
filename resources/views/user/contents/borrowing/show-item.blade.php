@extends('user.layouts.dashboard-user')

@section('title', 'Item Details')

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
                <h1 class="text-3xl font-bold text-gray-900">Item Details</h1>
                <p class="text-gray-600 mt-2">Complete information about items available for borrowing</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
            {{-- Image Section --}}
            <div class="space-y-4">
                <div class="aspect-square w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                    @if($item->gambar)
                        <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}" 
                             class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center">
                            <div class="rounded-full border-2 border-gray-200 p-12">
                                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                    stroke-linecap="round" stroke-linejoin="round" class="text-gray-300">
                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                    <circle cx="9" cy="9" r="2" />
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Details Section --}}
            <div class="space-y-6">
                {{-- Title and Stock --}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $item->nama }}</h2>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $item->stok_peminjaman }} units available
                        </span>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            Borrowing Item
                        </span>
                    </div>
                </div>

                {{-- Item Information --}}
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Item Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Available Stock:</span>
                                <span class="font-medium text-green-600">{{ $item->stok_peminjaman }} unit</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Added:</span>
                                <span class="font-medium text-gray-900">{{ $item->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($item->keterangan)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $item->keterangan }}</p>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3 pt-6 border-t border-gray-200">
                    @if($item->stok_peminjaman > 0)
                        <a href="{{ route('user.borrowing.create', $item->id) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg text-center inline-block transition-colors duration-200 font-medium">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Submit Borrowing Request for This Item
                        </a>
                    @else
                        <div class="w-full bg-gray-100 text-gray-500 py-3 px-6 rounded-lg text-center font-medium">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                            </svg>
                            Stock Not Available
                        </div>
                    @endif
                    
                    <a href="{{ route('user.borrowing.index') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg text-center inline-block transition-colors duration-200 font-medium">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Item List
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Items or Additional Info --}}
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Borrowing Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 mb-1">Submit Request</h4>
                <p class="text-sm text-gray-600">Fill out the borrowing form completely</p>
            </div>
            
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 mb-1">Pending Approval</h4>
                <p class="text-sm text-gray-600">Admin will review your request</p>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 mb-1">Pick Up Item</h4>
                <p class="text-sm text-gray-600">After approval, pick up the item according to schedule</p>
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection