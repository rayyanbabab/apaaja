{{-- Dark Mode Toggle Button --}}
<button id="dm-toggle"
    onclick="toggleDarkMode()"
    title="Toggle Dark Mode"
    class="dm-toggle-btn flex h-9 w-9 items-center justify-center rounded-xl transition-all duration-200 focus:outline-none"
    aria-label="Toggle dark mode">
    {{-- Sun icon (shown in dark mode) --}}
    <svg id="dm-icon-sun" class="w-[18px] h-[18px] hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    {{-- Moon icon (shown in light mode) --}}
    <svg id="dm-icon-moon" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
</button>

<script>
    function updateDarkModeIcons(isDark) {
        var sun  = document.getElementById('dm-icon-sun');
        var moon = document.getElementById('dm-icon-moon');
        if (!sun || !moon) return;
        if (isDark) {
            sun.classList.remove('hidden');
            moon.classList.add('hidden');
        } else {
            sun.classList.add('hidden');
            moon.classList.remove('hidden');
        }
    }
    // Set icon state on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        updateDarkModeIcons(document.documentElement.classList.contains('dark'));
    });
</script>
