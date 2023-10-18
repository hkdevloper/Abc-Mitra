/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./src/**/*.{html,js}",
        "./node_modules/tw-elements/dist/js/**/*.js"
    ],
  theme: {
    extend: {
      colors: {
        'ot': {
          '--font-family': 'Satoshi, "Satoshi Fallback"',
          '--P100': '#275df5',
          '--P200': '#b0c8ff',
          '--S100': '#f05537',
          '--S200': '#ffbfad',
          '--N100': '#fff',
          '--N200': '#fff',
          '--N300': '#f7f7f9',
          '--N400': '#e7e7f1',
          '--N500': '#979ec2',
          '--N600': '#717b9e',
          '--N700': '#474d6a',
          '--N800': '#121224',
          '--I100': '#f04141',
          '--I200': '#47b749',
          '--I300': '#fdaa29',
          '--I400': '#8951ff',
          '--A100': '#ffedf2',
          '--A200': '#efedff',
          '--A300': '#ecf5ff',
          '--A400': '#fff3dd',
          '--A500': '#ffedf2',
          '--D100': '#0d1734',
          '--D200': '#35063e',
          '--G100_T': '#fff',
          '--G100_B': '#ffedf2',
          '--G200_T': '#fff',
          '--G200_B': '#efedff',
          '--G300_T': '#fff',
          '--G300_B': '#edf4ff',
          '--G400_T': '#fff',
          '--G400_B': '#fff3dd',
          '--V100': 'rgba(236, 245, 255, 0.5019607843137255)',
          '--box-shadow1': '0px 6px 12px rgba(30, 10, 58, 0.04)',
          '--box-shadow2': '0px 6px 25px rgba(30, 10, 58, 0.08)',
          '--border': '1px solid #e7e7f1',
          '--br10': '10px',
          '--background': '#f5f5f5',
          '--missingDetailsBg': '#fff5f5',
          '--profilePerfBg': '#ecf5ff',
        },
      },
    },
  },
    plugins: [
        require('@tailwindcss/forms'),
        require("tw-elements/dist/plugin.cjs")
    ],
    darkMode: "class"
}
