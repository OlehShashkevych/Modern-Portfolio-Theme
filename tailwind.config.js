/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./blocks/**/*.php",
    "./inc/**/*.php",
    "./src/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        ag: {
          black: '#000000',
          dark: '#0a0a0a',
          darkgray: '#171717',
          light: '#f9f9f9',
          white: '#ffffff',
          accent: '#0d6efd', // Google blue-ish
        }
      },
      fontFamily: {
        sans: ['"Inter"', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
      },
      borderRadius: {
        '4xl': '2rem',
        '5xl': '2.5rem',
      }
    },
  },
  plugins: [],
}
