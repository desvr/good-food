/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                'primary': {
                    'dark'  : '#e11d48',
                    DEFAULT : '#f43f5e',
                    'light' : '#fff1f2',
                },
                'secondary': {
                    DEFAULT       : '#1e293b',
                    'very-dark'   : '#8591a2',
                    'dark'        : '#cbd5e1',
                    'bright'      : '#e2e8f0',
                    'semi-bright' : '#e8ecf0',
                    'semi-light'  : '#f1f5f9',
                    'light'       : '#f8fafc',
                    'very-light'  : '#fdfeff',
                },
            },
            keyframes: {
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-1deg)' },
                    '50%': { transform: 'rotate(1deg)' },
                }
            },
            animation: {
                wiggle: 'wiggle 1s ease-in-out 2.25',
                'bounce-slow': 'bounce 1s linear 1.5',
            },
            scale: {
                '102': '1.02',
            },
            zIndex: {
                '60': '60',
                '70': '70',
                '80': '80',
                '90': '90',
                '100': '100',
                '110': '110',
                '120': '120',
                '130': '130',
                '140': '140',
                '150': '150',
                '160': '160',
                '170': '170',
                '180': '180',
                '190': '190',
                '200': '200',
            }
        },
    },
    plugins: [
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/aspect-ratio'),
    ],
}
