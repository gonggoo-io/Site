/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      gridTemplateColumns: {
        '20': 'repeat(20, 64px)',
        '16': 'repeat(16, 64px)',
        '12': 'repeat(12, 64px)',
        '8': 'repeat(8, 64px)',
        '4': 'repeat(4, 64px)',
      },
      maxWidth: {
        'grid-20': 'calc(64px * 20 + 24px * 19)',
        'grid-16': 'calc(64px * 16 + 24px * 15)',
        'grid-12': 'calc(64px * 12 + 24px * 11)',
        'grid-8': 'calc(64px * 8 + 24px * 7)',
        'grid-4': 'calc(64px * 4 + 24px * 3)',
      },
    },
  },
  plugins: [],
} 