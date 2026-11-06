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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50: '#f0f5ff',
                    100: '#e0ecff',
                    200: '#c7dcff',
                    300: '#a1c4fd',
                    400: '#6ea2f9',
                    500: '#437ef3',
                    600: '#275fe6',
                    700: '#1d48c8',
                    800: '#1a3ca2',
                    900: '#1b357f',
                    950: '#0f172a',
                    dark: '#0a1128',
                },
                seven: {
                    primary: '#0F1E36',
                    secondary: '#1E3A8A',
                    accent: '#2563EB',
                    highlight: '#F59E0B',
                }
            },
            backgroundImage: {
                'navy-gradient': 'linear-gradient(135deg, #0B192C 0%, #1E3E62 50%, #0F172A 100%)',
                'navy-card': 'linear-gradient(180deg, #1E293B 0%, #0F172A 100%)',
                'accent-gradient': 'linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%)',
                'hero-pattern': 'radial-gradient(ellipse at top, #1e3a8a 0%, #0a1128 70%)',
            }
        },
    },

    plugins: [forms],
};
