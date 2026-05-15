<form method="GET" action="{{ route($routePrefix . '.inventory.index') }}" class="flex gap-x-2">
    <input
        type="text"
        name="query"
        value="{{ request('query') }}"
        placeholder="{{ $placeholder ?? 'Search items...' }}"
        class="input-form rounded-lg pr-20 py-2 text-sm"
        autocomplete="off"
    />
    <button type="submit" class="text-sm link-secondary rounded-lg">Search</button>
</form>
