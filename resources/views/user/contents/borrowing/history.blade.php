@extends('user.layouts.dashboard-user')

@section('title', 'Borrowing History')

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
    
    /* Smooth gradient animation */
    @keyframes gradient {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }
</style>
@endpush

@section('user')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Borrowing History</h1>
                <p class="text-sm text-gray-600 mt-1">View history of completed, cancelled, and rejected borrowing requests</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('user.borrowing.my-requests') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition-all duration-200 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Borrowing Status</span>
                </a>
                <a href="{{ route('user.borrowing.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Submit Borrowing Request</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div>
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($requests->count() > 0)
            <div class="space-y-3">
                @foreach($requests as $request)
                    <div class="bg-white border border-gray-200 rounded-xl hover:border-blue-400 hover:shadow-md transition-all duration-200">
                        <div class="p-5">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Left Section - Item Info -->
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <!-- Item Details -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-semibold text-gray-900 mb-2">{{ $request->item->nama }}</h3>
                                            <div class="flex flex-wrap gap-2 text-xs">
                                                <div class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md font-medium">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                    </svg>
                                                    {{ $request->jumlah }} unit
                                                </div>
                                                <div class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-700 rounded-md font-medium">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $request->tanggal_pinjam->format('d M') }} - {{ $request->tanggal_kembali_rencana->format('d M Y') }}
                                                </div>
                                            </div>
                                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Submitted {{ $request->created_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }} WIB
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Section - Status & Action -->
                                <div class="flex flex-col items-start lg:items-end gap-2.5">
                                    <!-- Status Badge -->
                                    @switch($request->status)
                                        @case('rejected')
                                            <div class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Rejected
                                            </div>
                                            @break
                                        @case('completed')
                                            <div class="flex flex-col items-end gap-1.5">
                                                <div class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Completed
                                                </div>
                                                @if($request->completed_at)
                                                    <span class="text-xs text-gray-500">{{ $request->completed_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }} WIB</span>
                                                @endif
                                            </div>
                                            @break
                                        @case('cancelled')
                                            <div class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Cancelled
                                            </div>
                                            @break
                                        @default
                                            <div class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ ucfirst($request->status) }}
                                            </div>
                                    @endswitch

                                    <!-- Action Button -->
                                    <a href="{{ route('user.borrowing.show', $request->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 py-12">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No borrowing history yet</h3>
                    <p class="text-sm text-gray-500 mb-6">You don't have any completed, cancelled, or rejected borrowing history</p>
                    <a href="{{ route('user.borrowing.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200 gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Submit Borrowing Request</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection