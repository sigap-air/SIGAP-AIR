import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#0F4C81',
                secondary: '#00A878',
                danger: '#EF4444',
                warning: '#F59E0B',
                success: '#00A878',
                info: '#3B82F6',
                surface: '#F8FAFC',
                'card': '#FFFFFF',
                'role-admin': '#7C3AED',
                'role-supervisor': '#0F4C81',
                'role-petugas': '#059669',
                'role-masyarakat': '#2563EB',
            },
        },
    },

    plugins: [forms],
};
