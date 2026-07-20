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
                "primary": "#E8927A",
                "background-light": "#f6f7f8",
                "background-dark": "#131a1f",
            },
            fontFamily: {
                "display": ["Inter", "sans-serif"],
                "heading": ["Poppins", "sans-serif"]
            },
            borderRadius: { "DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "full": "9999px" },
        },
    },

    plugins: [forms],
};