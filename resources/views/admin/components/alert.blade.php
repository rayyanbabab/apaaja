@props([
    'type' => 'success',
    'duration' => 3000,
])

@if (session($type))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, {{ $duration }})" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform translate-y-2 scale-95"
         class="{{ $type }}-toast relative">
        
        <button @click="show = false" 
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        {{ session($type) }}
    </div>
@endif