<div class="mb-4 flex items-center justify-between">
    <x-heading-section title="History Table" title-color="text-base text-gray-700" subtitle="Here your items history and condition" subtitle-color="text-xs text-gray-400" />
    <div class="relative z-10" x-data="{ open: false }">
        <div class="vertical">
            <button onclick="printTable()" class="link-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-file-down-icon lucide-file-down">
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                    <path d="M12 18v-6" />
                    <path d="m9 15 3 3 3-3" />
                </svg>
                Export
            </button>
            <button @click="open = !open" class="link-secondary" aria-label="Filter options">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                </svg>
                Filter : <span class="text-gray-500">{{ $filter? ucfirst($filter) : "All" }}</span>
            </button>
        </div>
        <div x-show="open" @click.away="open = false"
            class="absolute right-0 z-50 mt-2 w-48 rounded-md border border-gray-200 bg-white shadow-lg"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95">
            @foreach (['sold', 'damaged', 'expired', 'lost'] as $status)
                <a href="{{ route($routePrefix . '.inventory.tab.history.status', $status) }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    {{ ucfirst($status) }}
                </a>
            @endforeach
            <a href="{{ route($routePrefix . '.inventory.tab.history') }}"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Show All
            </a>
        </div>
    </div>
</div>
<script>
    function printTable() {
        const table = document.querySelector('table').outerHTML;
        const style = `
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-family: sans-serif;
                }
                th, td {
                    border: 1px solid #999;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background: #f1f5f9;
                }
                td span.label {
                    all: unset;
                }
            </style>
        `;

        const win = window.open('', '', 'height=600,width=800');
        win.document.write('<html><head><title>Print History Table</title>');
        win.document.write(style);
        win.document.write('</head><body>');
        win.document.write('<h2>Inventory History {{ ucfirst($filter) }}</h2>');
        win.document.write(table);
        win.document.write('</body></html>');
        win.document.close();
        win.print();
    }
</script>

