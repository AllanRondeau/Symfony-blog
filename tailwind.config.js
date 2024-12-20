/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        black: '#28262C',
        primary: '#998FC7',
        secondary: '#D4C2FC',
        green: '#C3DAC3',
        lightGreen: '#D5ECD4',
      },
    },
  },
  plugins: [],
}

