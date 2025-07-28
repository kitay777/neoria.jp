import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'border-blue-500', 'bg-blue-100','bg-blue-500',
        'border-pink-500', 'bg-pink-100','bg-pink-500',
        'border-green-500', 'bg-green-100','bg-green-500',
        'border-yellow-500', 'bg-yellow-100','bg-yellow-500',
        'border-gray-200', 'bg-gray-100','bg-gray-500',
        // 必要に応じて追加
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'main-bg': '#81D8D0', // 任意の名前でOK
            },
        },
    },

    plugins: [forms,
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/container-queries'),
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/forms'),
    ],
};
