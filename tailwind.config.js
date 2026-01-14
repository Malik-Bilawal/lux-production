const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'class',
    content: [
      './resources/**/*.blade.php',
      './resources/**/*.js',
      './resources/**/*.vue',
      './resources/**/*.css',
      './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
  // tailwind.config.js
theme: {
    extend: {
        colors: {
            theme: {
                page: '#0f0f0f',
                surface: '#1a1a1a',
                deep: '#050505',
                primary: '#D4AF37',
                'primary-light': '#F6E6B6',
                'primary-dark': '#AA8C2C',
                content: '#FFFFFF',
                muted: '#9CA3AF',
                inverted: '#000000',
            }
        },
        fontFamily: {
            body: ['Montserrat', 'sans-serif'],
            heading: ['Cinzel', 'serif'],
            accent: ['Playfair Display', 'serif'],
        },
        letterSpacing: {
            tight: '-0.025em',
            normal: '0em',
            wide: '0.025em',
            wider: '0.05em',
            widest: '0.25em',
        },
        boxShadow: {
            'glow': '0 0 25px rgba(212, 175, 55, 0.25)',
            'card': '0 15px 40px -10px rgba(0, 0, 0, 0.9)',
            'legal-card': '0 10px 30px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1)',
            'emboss': 'inset 0 1px 0 rgba(255, 255, 255, 0.05), 0 8px 20px rgba(0, 0, 0, 0.6)',
            'gold-emboss': 'inset 0 1px 0 rgba(212, 175, 55, 0.2), 0 4px 20px rgba(0, 0, 0, 0.4)',
        },
        animation: {
            'spin-slow': 'spin 12s linear infinite',
            'spin-reverse-slow': 'spin 15s linear reverse infinite',
            'fade-in-up': 'fadeInUp 0.6s ease-out',
            'fade-in': 'fadeIn 0.8s ease-out',
            'pulse-subtle': 'pulse 4s ease-in-out infinite',
        },
        backgroundImage: {
            'gradient-legal': 'linear-gradient(180deg, rgba(26, 26, 26, 0) 0%, rgba(212, 175, 55, 0.05) 100%)',
            'gradient-gold': 'linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.02) 100%)',
            'gradient-divider': 'linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent)',
        }
    }
},
    plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
    ],
  };
  