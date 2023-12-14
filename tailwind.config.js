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
        'greenD': '#22C55E',
        'purpleD': '#9333EA',
        'yellowD': '#EAB308',
        'pinkD': '#DB2777',
        'maincolor': '#3a6d80',
        'maincolorh': '#006d80'
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

