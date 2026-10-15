import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./app/Http/Controllers/**/*.php",
        "./app/Services/**/*.php",
    ],
    safelist: [
        'bg-amber-500/10', 'text-amber-700', 'dark:text-amber-300', 'border-amber-500/20',
        'bg-blue-500/10', 'text-blue-700', 'dark:text-blue-300', 'border-blue-500/20',
        'bg-teal-500/10', 'text-teal-700', 'dark:text-teal-300', 'border-teal-500/20',
        'bg-sky-500/10', 'text-sky-700', 'dark:text-sky-300', 'border-sky-500/20',
        'bg-purple-500/10', 'text-purple-700', 'dark:text-purple-300', 'border-purple-500/20',
        'bg-rose-500/10', 'text-rose-700', 'dark:text-rose-300', 'border-rose-500/20',
        'bg-indigo-500/10', 'text-indigo-700', 'dark:text-indigo-300', 'border-indigo-500/20',
        'bg-slate-500/10', 'text-slate-700', 'dark:text-slate-300', 'border-slate-500/20',
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary": "#699ff6ff",
                "background-light": "#f8f8f8ff",
                "background-dark": "#0f172a",
            },
            fontFamily: {
                "sans": ["Inter", "system-ui", "-apple-system", "sans-serif"],
                "display": ["Inter", "system-ui", "-apple-system", "sans-serif"],
                "heading": ["Inter", "system-ui", "-apple-system", "sans-serif"],
            },
            borderRadius: { "DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "full": "9999px" },
        },
    },

    plugins: [forms],
};