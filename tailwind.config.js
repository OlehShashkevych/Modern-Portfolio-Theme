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
          bg: '#ffffff',       // pure white main background
          black: '#121317',    // dark black with a hint of blue
          dark: '#1c1e26',     // slightly lighter dark
          light: '#ffffff',    // pure white
          white: '#ffffff',
          accent: 'var(--ag-accent)',   // map to css variable
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
