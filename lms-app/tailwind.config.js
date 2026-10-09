import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
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