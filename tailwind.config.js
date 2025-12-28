/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/@fortawesome/fontawesome-free/**/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}