import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';
import lineClamp from '@tailwindcss/line-clamp';

export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/**/*.php',
    ],
    safelist: [
        // Background colors (dynamic / PHP-generated)
        { pattern: /^bg-(blue|green|red|yellow|orange|purple|indigo|gray|amber|teal|emerald|rose|slate|sky|violet)-(50|100|200|300|400|500|600|700|800|900)$/ },
        { pattern: /^bg-(blue|green|red|yellow|orange|purple|indigo|gray|amber|teal|emerald|rose|slate)-(50|100|200|300|400|500|600|700|800|900)\/(10|20|30|40|50|60|70|80|90)$/ },
        // Text colors
        { pattern: /^text-(blue|green|red|yellow|orange|purple|indigo|gray|amber|teal|emerald|rose|slate|sky|violet)-(50|100|200|300|400|500|600|700|800|900)$/ },
        // Border colors
        { pattern: /^border-(blue|green|red|yellow|orange|purple|indigo|gray|amber|teal|emerald|rose|slate)-(50|100|200|300|400|500|600|700|800|900)$/ },
        // Ring colors
        { pattern: /^ring-(blue|green|red|yellow|orange|purple|indigo|gray|amber|teal|emerald|rose|slate)-(50|100|200|300|400|500|600|700|800|900)$/ },
        // Gradient from/to/via
        { pattern: /^from-(blue|green|red|yellow|orange|purple|indigo|gray|amber|teal|emerald|rose|slate|sky|violet)-(50|100|200|300|400|500|600|700|800|900)$/ },
        { pattern: /^to-(blue|green|red|yellow|orange|purple|indigo|gray|amber|teal|emerald|rose|slate|sky|violet)-(50|100|200|300|400|500|600|700|800|900)$/ },
        { pattern: /^via-(blue|green|red|yellow|orange|purple|indigo|gray|amber|teal|emerald|rose|slate|sky|violet)-(50|100|200|300|400|500|600|700|800|900)$/ },
        // Layout utilities
        'lg:col-span-1', 'lg:col-span-2', 'lg:col-span-3',
        'md:col-span-2', 'md:col-span-3',
        'sm:col-span-1', 'sm:col-span-2',
        // Responsive show/hide
        'hidden', 'block', 'flex', 'grid',
        'sm:hidden', 'sm:block', 'sm:flex',
        'md:hidden', 'md:block', 'md:flex',
        'lg:hidden', 'lg:block', 'lg:flex',
    ],
    theme: {
        extend: {
            fontFamily: {
                vietnam: ['BeVietnamPro', 'sans-serif'],
            },
        },
    },
    plugins: [
        forms,
        typography,
        aspectRatio,
        lineClamp,
    ],
};
