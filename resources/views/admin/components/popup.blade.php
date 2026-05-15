@props([
    'id',
    'title'        => 'Konfirmasi',
    'message'      => 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    'formId'       => null,
    'onConfirm'    => null,
    'confirmText'  => 'Hapus',
    'cancelText'   => 'Batal',
    'confirmClass' => 'inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm',
    'cancelClass'  => 'inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors',
    'icon'         => 'danger',
])

<div id="{{ $id }}"
     class="flex fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background: rgba(0,0,0,0.5); backdrop-filter: blur(3px);">
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden animate-in">

        {{-- Header --}}
        <div class="flex items-center gap-3 px-6 pt-6 pb-4">
            @if($icon === 'danger')
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            @elseif($icon === 'info')
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <h3 class="text-base font-extrabold text-gray-900">{{ $title }}</h3>
            </div>
            <button onclick="closeModal('{{ $id }}')"
                    class="flex-shrink-0 p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 pb-5">
            <p class="text-sm text-gray-500 leading-relaxed">{{ $message }}</p>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2.5 px-6 py-4 bg-gray-50 border-t border-gray-100">
            <button onclick="closeModal('{{ $id }}')" class="{{ $cancelClass }}">{{ $cancelText }}</button>
            @if ($onConfirm)
                <button onclick="({!! $onConfirm !!})(); closeModal('{{ $id }}')" class="{{ $confirmClass }}">
                    {{ $confirmText }}
                </button>
            @elseif ($formId)
                <button onclick="document.getElementById('{{ $formId }}').submit(); closeModal('{{ $id }}')" class="{{ $confirmClass }}">
                    {{ $confirmText }}
                </button>
            @endif
        </div>
    </div>
</div>

@once
    <style>
        @keyframes modal-in {
            from { opacity: 0; transform: scale(0.95) translateY(8px); }
            to   { opacity: 1; transform: scale(1)    translateY(0); }
        }
        .animate-in { animation: modal-in 0.2s ease-out both; }
    </style>
    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('hidden');
            el.querySelector('.animate-in')?.style.removeProperty('animation');
            void el.querySelector('.animate-in')?.offsetWidth;
            if (el.querySelector('.animate-in')) {
                el.querySelector('.animate-in').style.animation = 'modal-in 0.2s ease-out both';
            }
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
@endonce
