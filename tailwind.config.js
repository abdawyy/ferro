/** @type {import('tailwindcss').Config} */
// ─────────────────────────────────────────────────────────────────────────────
// FERRO Design System — Tailwind Configuration
// Brand Palette: Iron Black · Forge Orange · Pure White · Obsidian · Ash Grey
// ─────────────────────────────────────────────────────────────────────────────
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    // RTL/LTR support via dir attribute on <html>
    // Tailwind's built-in `rtl:` and `ltr:` variants are enabled by default in v3.3+
    theme: {
        extend: {
            // ── Brand Color Tokens ────────────────────────────────────────────
            colors: {
                ferro: {
                    // Primary
                    orange:      '#E8500A', // Forge Orange — CTA, accents, highlights
                    'orange-deep': '#C43E06', // hover / pressed state
                    'orange-glow': '#FF6B2B', // ambient glow effects

                    // Neutrals
                    black:       '#0A0A0A', // Iron Black — backgrounds, text
                    'black-soft': '#111111', // cards on dark backgrounds
                    obsidian:    '#1A1A1A', // elevated surface
                    carbon:      '#2A2A2A', // borders, dividers
                    ash:         '#6B6B6B', // secondary text / muted
                    silver:      '#B0B0B0', // placeholder, disabled
                    'off-white':  '#F5F2EE', // warm white — body text on dark
                    white:        '#FFFFFF', // Pure White — headlines, icons

                    // Status
                    'coming-soon': '#E8500A',
                    'in-stock':    '#22C55E',
                    'out-of-stock':'#EF4444',
                },
            },

            // ── Typography ────────────────────────────────────────────────────
            fontFamily: {
                // Display — editorial headlines (luxury feel)
                display: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                // Body — clean, athletic legibility
                body:    ['"Inter"', 'system-ui', 'sans-serif'],
                // Arabic — premium Arabic typography
                arabic:  ['"Noto Kufi Arabic"', '"Cairo"', 'sans-serif'],
                // Mono — technical / ingredient labels
                mono:    ['"JetBrains Mono"', 'monospace'],
            },

            fontSize: {
                // Display scale
                'display-2xl': ['clamp(3.5rem,  8vw, 7rem)',   { lineHeight: '1.0', letterSpacing: '-0.03em' }],
                'display-xl':  ['clamp(2.5rem,  5vw, 4.5rem)', { lineHeight: '1.05', letterSpacing: '-0.02em' }],
                'display-lg':  ['clamp(1.75rem, 3vw, 3rem)',   { lineHeight: '1.1',  letterSpacing: '-0.015em' }],
                'body-lg':     ['1.125rem', { lineHeight: '1.75' }],
                'body-sm':     ['0.875rem', { lineHeight: '1.6' }],
                'label':       ['0.75rem',  { lineHeight: '1.5', letterSpacing: '0.12em' }],
            },

            // ── Spacing (8pt grid + luxury large whitespace) ──────────────────
            spacing: {
                '18':  '4.5rem',
                '22':  '5.5rem',
                '30':  '7.5rem',
                '34':  '8.5rem',
                '38':  '9.5rem',
                '42':  '10.5rem',
                '50':  '12.5rem',
                '72':  '18rem',
                '84':  '21rem',
                '96':  '24rem',
                '128': '32rem',
            },

            // ── Box Shadows — premium depth ───────────────────────────────────
            boxShadow: {
                'forge':     '0 0 0 1px rgba(232,80,10,0.4)',
                'forge-glow':'0 0 40px rgba(232,80,10,0.25), 0 0 80px rgba(232,80,10,0.1)',
                'luxury':    '0 25px 80px rgba(0,0,0,0.6), 0 10px 30px rgba(0,0,0,0.4)',
                'product':   '0 20px 60px rgba(0,0,0,0.5)',
                'card':      '0 4px 24px rgba(0,0,0,0.35)',
                'inset-top': 'inset 0 2px 0 rgba(255,255,255,0.05)',
            },

            // ── Gradients ─────────────────────────────────────────────────────
            backgroundImage: {
                'forge-radial':  'radial-gradient(ellipse at 50% 0%, rgba(232,80,10,0.15) 0%, transparent 70%)',
                'iron-linear':   'linear-gradient(135deg, #0A0A0A 0%, #1A1A1A 50%, #0A0A0A 100%)',
                'orange-shine':  'linear-gradient(135deg, #E8500A 0%, #FF6B2B 50%, #C43E06 100%)',
                'hero-overlay':  'linear-gradient(to bottom, rgba(10,10,10,0) 40%, rgba(10,10,10,0.95) 100%)',
                'product-fade':  'linear-gradient(to top, rgba(10,10,10,1) 0%, rgba(10,10,10,0) 60%)',
                'noise':         "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E\")",
            },

            // ── Animations ────────────────────────────────────────────────────
            animation: {
                'fade-up':        'fadeUp 0.7s cubic-bezier(0.22,1,0.36,1) forwards',
                'fade-in':        'fadeIn 0.5s ease forwards',
                'scale-in':       'scaleIn 0.4s cubic-bezier(0.22,1,0.36,1) forwards',
                'shimmer':        'shimmer 2.5s linear infinite',
                'pulse-forge':    'pulseForge 2s ease-in-out infinite',
                'glow-orbit':     'glowOrbit 4s ease-in-out infinite',
                'count-up':       'countUp 0.8s ease-out forwards',
                'slide-in-rtl':   'slideInRTL 0.4s cubic-bezier(0.22,1,0.36,1) forwards',
                'slide-in-ltr':   'slideInLTR 0.4s cubic-bezier(0.22,1,0.36,1) forwards',
            },
            keyframes: {
                fadeUp:     { from: { opacity: 0, transform: 'translateY(30px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
                fadeIn:     { from: { opacity: 0 }, to: { opacity: 1 } },
                scaleIn:    { from: { opacity: 0, transform: 'scale(0.95)' }, to: { opacity: 1, transform: 'scale(1)' } },
                shimmer:    { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                pulseForge: { '0%,100%': { boxShadow: '0 0 20px rgba(232,80,10,0.3)' }, '50%': { boxShadow: '0 0 40px rgba(232,80,10,0.6)' } },
                glowOrbit:  { '0%,100%': { transform: 'translateX(0) translateY(0)' }, '25%': { transform: 'translateX(5px) translateY(-5px)' }, '75%': { transform: 'translateX(-5px) translateY(5px)' } },
                countUp:    { from: { opacity: 0, transform: 'translateY(10px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
                slideInRTL: { from: { opacity: 0, transform: 'translateX(20px)' }, to: { opacity: 1, transform: 'translateX(0)' } },
                slideInLTR: { from: { opacity: 0, transform: 'translateX(-20px)' }, to: { opacity: 1, transform: 'translateX(0)' } },
            },

            // ── Border Radius ─────────────────────────────────────────────────
            borderRadius: {
                'luxury': '2px',    // near-square — premium minimal
                'card':   '8px',
                'pill':   '9999px',
            },

            // ── Z-index scale ─────────────────────────────────────────────────
            zIndex: {
                'nav':      '100',
                'drawer':   '200',
                'overlay':  '300',
                'modal':    '400',
                'toast':    '500',
            },

            // ── Aspect Ratios ─────────────────────────────────────────────────
            aspectRatio: {
                'product':    '3 / 4',
                'hero':       '16 / 9',
                'hero-tall':  '4 / 5',
                'square':     '1 / 1',
            },

            // ── Screens ───────────────────────────────────────────────────────
            screens: {
                'xs': '375px',
                // sm, md, lg, xl, 2xl as default
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        // RTL plugin for advanced RTL mirroring (arrows, icons, padding)
        require('tailwindcss-rtl'),
    ],
};
