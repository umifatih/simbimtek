/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        ink: {
          DEFAULT: '#16232E', // teks utama
          700: '#1C3A5C',     // hover state elemen gelap
          900: '#0F2A43',     // navy utama — nav, tombol, footer
        },
        gold: {
          DEFAULT: '#C99A3D', // aksen — dipakai tipis, untuk status & signature
          600: '#B4872F',
        },
        canvas: '#F5F7F6',     // latar halaman
        line: '#DDE3E8',       // border / pembatas
        success: '#1F7A5C',    // status "kuota tersedia" dsb.
      },
      fontFamily: {
        display: ['Sora', 'sans-serif'],
        body: ['"Plus Jakarta Sans"', 'sans-serif'],
        mono: ['"JetBrains Mono"', 'monospace'],
      },
    },
  },
  plugins: [],
}