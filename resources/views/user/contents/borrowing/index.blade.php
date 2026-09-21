@extends('user.layouts.dashboard-user')
@section('title', 'Item Borrowing')
@section('user')
<div class="space-y-6">

    {{-- ── Page Header ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="relative px-5 sm:px-7 py-6">
            {{-- Decorative gradient blob --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-10 -right-10 w-48 h-48 bg-gradient-to-br from-blue-100 to-indigo-50 rounded-full opacity-60 blur-2xl"></div>
            </div>

            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full">Borrowing</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">Item Borrowing</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Browse and select items you want to borrow</p>
                </div>

                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    {{-- Cart Badge --}}
                    <a href="{{ route('user.borrowing.cart') }}"
                       class="relative inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Cart
                    </a>
                    <a href="{{ route('user.borrowing.history') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        History
                    </a>
                    <a href="{{ route('user.borrowing.my-requests') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        My Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
            <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- ── Tooling Kits (Paket Job Order SPK) ── --}}
    @if(isset($toolingKits) && $toolingKits->count() > 0)
        <div class="bg-gradient-to-br from-amber-500/10 via-amber-50 to-orange-50/60 rounded-2xl border border-amber-200/80 p-5 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2M5 9V7a2 2 0 012-2h6a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Paket Perkakas Job Order (SPK)</h2>
                        <p class="text-xs text-gray-500">Pinjam seperangkat alat pemesinan lengkap sekaligus dengan 1 klik</p>
                    </div>
                </div>
                <span class="text-[11px] font-semibold text-amber-800 bg-amber-100/80 border border-amber-200 px-2.5 py-1 rounded-full">
                    Standar Manufaktur
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-1">
                @foreach($toolingKits as $kit)
                <div class="bg-white rounded-xl border border-amber-200/70 p-4 shadow-sm flex flex-col justify-between space-y-3 hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded">{{ $kit->kode }}</span>
                            <span class="text-[11px] text-gray-400">{{ $kit->target_machine ?? 'Mesin Umum' }}</span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mt-1">{{ $kit->nama }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $kit->deskripsi }}</p>

                        <div class="mt-2.5 pt-2 border-t border-gray-100 text-[11px] text-gray-600 space-y-1">
                            <p class="font-semibold text-gray-700">Termasuk:</p>
                            @foreach($kit->kitItems as $kItem)
                                <div class="flex items-center justify-between text-[11px] text-gray-600">
                                    <span class="truncate">&bull; {{ $kItem->item->nama ?? '-' }}</span>
                                    <span class="font-bold text-gray-800">{{ $kItem->jumlah }}x</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <form method="POST" action="{{ route('user.borrowing.kit.borrow', $kit->id) }}" class="pt-2">
                        @csrf
                        <button type="submit"
                                class="w-full py-2 px-3 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Pinjam 1 Paket ke Keranjang
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Items Grid ── --}}
    @if($items->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($items as $item)
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden flex flex-col">

                    {{-- Image --}}
                    <div class="relative h-44 w-full overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 flex-shrink-0">
                        @if ($item->gambar)
                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}"
                                 class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-200 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-gray-400">No image</span>
                                </div>
                            </div>
                        @endif

                        {{-- Stock badge --}}
                        @php $stok = $item->stok_peminjaman; @endphp
                        <div class="absolute top-2.5 right-2.5">
                            <span class="inline-flex items-center gap-1 {{ $stok > 3 ? 'bg-emerald-500' : ($stok > 0 ? 'bg-amber-500' : 'bg-red-500') }} text-white text-[11px] font-semibold px-2 py-0.5 rounded-full shadow">
                                <span class="w-1.5 h-1.5 bg-white/70 rounded-full"></span>
                                {{ $stok }} available
                            </span>
                        </div>

                        {{-- Category badge --}}
                        @if($item->category)
                            <div class="absolute bottom-2.5 left-2.5">
                                <span class="bg-black/40 backdrop-blur-sm text-white text-[10px] font-medium px-2 py-0.5 rounded-full">
                                    {{ $item->category->nama }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Card Body: split into top (flexible) + bottom (fixed actions) --}}
                    <div class="p-4 flex flex-col flex-1">

                        {{-- Top zone: flexible content --}}
                        <div class="flex-1 flex flex-col gap-1 mb-3">
                            {{-- Title --}}
                            <h3 class="text-sm font-bold text-gray-900 truncate">{{ $item->nama }}</h3>

                            {{-- Description (always reserve space for 1 line) --}}
                            <p class="text-xs text-gray-400 line-clamp-1 min-h-[1rem]">
                                @if($item->keterangan)
                                    {{ Str::limit($item->keterangan, 60) }}
                                @else
                                    &nbsp;
                                @endif
                            </p>

                            {{-- Location --}}
                            @if($item->location)
                                @php $locName = $item->location->nama ?? $item->location->name ?? ''; @endphp
                                @if($locName)
                                    <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $locName }}
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Bottom zone: always pinned to bottom --}}
                        <div class="space-y-2">
                            {{-- Quantity + Add to Cart --}}
                            <form action="{{ route('user.borrowing.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex-shrink-0">
                                        <button type="button" onclick="decrementQty(this)"
                                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors text-base font-bold">
                                            −
                                        </button>
                                        <input type="number" name="jumlah" value="1" min="1" max="{{ $stok }}"
                                               class="w-9 h-8 text-center text-sm font-semibold text-gray-800 bg-transparent border-0 focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                        <button type="button" onclick="incrementQty(this, {{ $stok }})"
                                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors text-base font-bold">
                                            +
                                        </button>
                                    </div>
                                    <button type="submit"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold h-8 px-2 rounded-lg transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Add to Cart
                                    </button>
                                </div>
                            </form>

                            {{-- Detail + Borrow --}}
                            <div class="flex gap-2">
                                <a href="{{ route('user.borrowing.show-item', $item->id) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold h-8 px-2 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                                <a href="{{ route('user.borrowing.create', $item->id) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold h-8 px-2 rounded-lg transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    Borrow Now
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($items instanceof \Illuminate\Contracts\Pagination\Paginator && $items->hasPages())
            <div class="flex justify-center">
                {{ $items->links() }}
            </div>
        @endif

    @else
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-20 text-center">
            <div class="flex items-center justify-center w-16 h-16 bg-blue-50 rounded-2xl mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">No Items Available</h3>
            <p class="text-sm text-gray-400 max-w-xs mx-auto">There are currently no items available for borrowing. Please check back later.</p>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function decrementQty(btn) {
    const input = btn.parentElement.querySelector('input[type="number"]');
    const min = parseInt(input.min) || 1;
    if (parseInt(input.value) > min) input.value = parseInt(input.value) - 1;
}
function incrementQty(btn, max) {
    const input = btn.parentElement.querySelector('input[type="number"]');
    if (parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
}
</script>
@endpush