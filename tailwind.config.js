module.exports = {
  purge: ['./assets/**/*.{vue,js}'],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [
      require('daisyui')
  ],
  daisyui: {
    themes: [
        'dark'
    ]
  }
}
