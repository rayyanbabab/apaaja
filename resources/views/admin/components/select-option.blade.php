@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Pilih opsi...',
])

@php
    $id = 'select-' . $name;
@endphp

<div x-data="{
    open: false,
    selected: '{{ $selected }}',
    options: @js($options),
    choose(value) {
        this.selected = value;
        this.open = false;
    }
}" x-init="$watch('selected', value => $refs.input.value = value)" class="space-y-2 relative w-full">
    @if ($label)
        <label for="{{ $id }}" class="label-form">
            {{ $label }}
        </label>
    @endif

    <input type="hidden" name="{{ $name }}" x-ref="input" :value="selected">

    <button type="button" @click="open = !open" @click.away="open = false"
        class="input-form flex items-center justify-between border border-gray-300 px-4 py-2 text-left group">
        <span class='text-gray-500 text-sm' x-text="options[selected] || '{{ $placeholder }}'"></span>

        <svg class="group-hover: h-4 w-4 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <ul x-show="open" x-transition
        class="absolute z-50 mt-2 max-h-60 w-full overflow-auto rounded-md border border-neutral-200 bg-white shadow-lg">
        <template x-for="[value, label] of Object.entries(options)" :key="value">
            <li @click="choose(value)" class="option-form"
                :class="{ 'bg-violet-100 text-violet-600 font-medium': selected === value }">
                <span x-text="label"></span>
            </li>
        </template>
    </ul>
</div>
