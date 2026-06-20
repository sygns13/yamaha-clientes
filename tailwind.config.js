import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans:    ['Figtree', ...defaultTheme.fontFamily.sans],
                estricta: ['Estricta', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                yamaha: {
                    blue:       '#003087',
                    'blue-dark':'#001f5c',
                    'blue-mid': '#0040b0',
                    red:        '#E60012',
                    'red-dark': '#b3000e',
                },
            },
        },
    },

    plugins: [forms],
};
