/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'primary': '#1479A3',
                'secondary': '#834D1E',
            },
        },

    },
    plugins: [

    ],
    important: true,
    prefix: "tw-",
    corePlugins: {
        preflight: false,
    }
}
