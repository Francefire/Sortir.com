/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    theme: {
        extend: {
            fontFamily: {
                inter: ['Inter'],
            },
            color: {
                'white': '#CFDBD5',
                'white-green': '#E8EDDF',
                'yellow': '#F5CB5C',
                'yellow-darker': '#F3C33F',
                'darkest': '#242423',
                'gray-dark': '#333533',

                'color-one': '#CFDBD5',
                'color-two': '#E8EDDF',
                'color-three': '#F5CB5C',
                'color-three-darker': '#F3C33F',
                'color-four': '#242423',
                'color-five': '#333533',

            }
        },
    },
    plugins: [],
}
