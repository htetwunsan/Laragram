const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.jsx',
    ],
    theme: {
        extend: {
            colors: {
                fb_blue: 'rgb(0, 149, 246)',
                soft_fb_blue: 'rgb(224, 241, 255)',
                triple142: 'rgb(142, 142, 142)',
                triple38: 'rgb(38, 38, 38)',
                triple199: 'rgb(199, 199, 199)',
                triple219: 'rgb(219, 219, 219)',
                triple239: 'rgb(239, 239, 239)',
                triple250: 'rgb(250, 250, 250)',
                error: 'rgb(237, 73, 86)',
            },
            fontFamily: {
                cookie: [
                    'Cookie',
                ],
                mono: [
                    'JetBrains Mono',
                    ...defaultTheme.fontFamily.mono
                ]
            },
            lineHeight: {
                '18px': '18px',
            },
            opacity: {
                35: '0.35',
                65: '0.65',
            },
            transitionDuration: {
                '7s': '7s',
            },
            transitionProperty: {
                width: 'width',
            },
            zIndex: {
                1000: '1000',
                '-1000': '-1000',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms')
    ],
};
