/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/View/**/*.php",
    "./public/assets/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        primary: "#1C1917", // Optional override if using Tailwind classes directly
      },
    },
  },
  plugins: [],
}
