/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    theme: {
        colors: {
            'color-one': '#CFDBD5',
            'color-two': '#E8EDDF',
            'color-three': '#F5CB5C',
            'color-three-darker': '#F3C33F',
            'color-four': '#242423',
            'color-five': '#333533',
        },
        extend: {
            colors: {
                'palette-light-dark': '#CFDBD5',
                'palette-light-light': '#E8EDDF',
                'palette-accent-light': '#F5CB5C',
                'palette-accent-dark': '#F3C33F',
                'palette-dark-dark': '#242423',
                'palette-dark-light': '#333533',

                'color-one': '#CFDBD5',
                'color-two': '#E8EDDF',
                'color-three': '#F5CB5C',
                'color-three-darker': '#F3C33F',
                'color-four': '#242423',
                'color-five': '#333533',

            },
            fontFamily: {
                inter: ['Inter'],
            }
        },
    },
    plugins: [],
}
