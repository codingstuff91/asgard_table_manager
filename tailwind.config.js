const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'bg-red-500',
        'bg-indigo-500',
        'bg-emerald-500',
        'bg-yellow-500',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                red: {
                    100: '#fdf8f6',
                }
            }
        },
        screens: {
            'xs': '320px',
            'sm': '640px',
            'lg': '1024px',
            'xl': '1280px',
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
