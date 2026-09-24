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
     class="flex fixed inset-0 z-[9999] hidden items-center justify-center p-4 overflow-y-auto"
     style="background: rgba(3, 7, 18, 0.6); backdrop-filter: blur(4px);">
    <div class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 overflow-hidden animate-in text-left">

        <div class="flex items-center gap-3 px-6 pt-6 pb-4 border-b border-gray-100 dark:border-slate-800 bg-gray-50/60 dark:bg-slate-850">
            @if($icon === 'danger')
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-100 dark:bg-red-950/60 flex items-center justify-center text-red-600 dark:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            @elseif($icon === 'info')
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <h3 class="text-base font-bold text-gray-900 dark:text-white leading-snug">{{ $title }}</h3>
            </div>
            <button onclick="closeModal('{{ $id }}')"
                    class="flex-shrink-0 p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <p class="text-xs sm:text-sm text-gray-600 dark:text-slate-300 leading-relaxed">{{ $message }}</p>
        </div>

        <div class="flex items-center justify-end gap-2.5 px-6 py-4 bg-gray-50/80 dark:bg-slate-850 border-t border-gray-100 dark:border-slate-800">
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
            if (!el) return;
            if (el.parentElement !== document.body) {
                document.body.appendChild(el);
            }
            el.classList.remove('hidden');
            el.querySelector('.animate-in')?.style.removeProperty('animation');
            void el.querySelector('.animate-in')?.offsetWidth;
            if (el.querySelector('.animate-in')) {
                el.querySelector('.animate-in').style.animation = 'modal-in 0.2s ease-out both';
            }
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        }
    </script>
@endonce
