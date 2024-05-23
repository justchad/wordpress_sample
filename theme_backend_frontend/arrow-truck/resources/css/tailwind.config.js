module.exports = {
  purge: [
    './components/*/*.php',
    './components/*/*.js',
    './templates/contents/*.php',
    './templates/partials/*.php',
    './templates/*.php',
    './*.php',
    './resources/js/*js',
    './lib/*.php',
    './lib/*/*.php',
    './lib/*/*/*.php'
  ],
  theme: {
    colors: {
      transparent: 'transparent',
      black: '#000',
      white: '#fff',
      brand: {
        'primary': '#C72027',
        'light-gray': '#F7F7F7',
        'dark-gray': '#1B1B1B',
        'scrim': 'rgba(0,0,0,0.4)',
        'overlay': 'rgba(255,255,255,0.35)',
      },
      gray: {
        100: '#F7F7F7',
        200: '#A4A4A4',
        250: '#ECECEC',
        300: '#757575', // replacing 7B7B7B for a11y
        400: '#464646',
        500: '#1B1B1B',
      },
      red: {
        50: '#DF8B8F',
        100: '#F73D44',
        200: '#C53D40',
        300: '#C72027',
        400: '#AD1519',
      },
    },
    spacing: {
      px: '1px',
      gutter: '1.5625rem',
      '0': '0',
      '1': '0.25rem', // 4px
      '5/10': '0.375rem', // 4px
      '1/2': '0.5rem', // 8px
      '2': '0.625rem', // 10px
      '3': '0.75rem', // 12px
      '4': '1rem', // 16px
      '5': '1.25rem', // 20px
      '6': '1.5rem', // 24px
      '7': '1.875rem', // 30px
      '8': '2rem', // 32px
      '9': '2.25rem', // 36px
      '10': '2.5rem', // 40px
      '12': '3rem', // 48px
      '13': '3.125rem', // 50px
      '16': '4rem', // 64px
      '20': '5rem', // 80px
      '24': '6rem', // 96px
      '25': '6.25rem', // 100px
      '28': '7rem',
      '30': '7.5rem', // 120px
      '32': '8rem', // 128px
      '38': '9.375rem', // 150px
      '40': '10rem', // 160px
      '48': '12rem', // 192px
      '56': '14rem', // 224px
      '64': '16rem', // 256px
    },
    screens: {
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1270px',
    },
    fontFamily: {
      sans: [
        '"Inter"',
        'sans-serif',
        '-apple-system',
        'BlinkMacSystemFont',
      ],
    },
    fontSize: {
      xs: 12 / 16 + 'rem',
      sm: 14 / 16 + 'rem',
      base: 16 / 16 + 'rem',
      lg: 18 / 16 + 'rem',
      xl: 20 / 16 + 'rem',
      xxl: 32 / 16 + 'rem',
      '2xl': 24 / 16 + 'rem',
      '3xl': 28 / 16 + 'rem',
      '4xl': 32 / 16 + 'rem',
      '5xl': 40 / 16 + 'rem',
      '6xl': 48 / 16 + 'rem',
      '7xl': 64 / 16 + 'rem',
    },
    fontWeight: {
      normal: '400',
      medium: '500',
      semibold: '600',
      bold: '700',
    },
    lineHeight: {
      zero: '0',
      none: '1',
      tight: '1.25',
      snug: '1.375',
      normal: '1.5',
      relaxed: '1.625',
      loose: '2',
    },
    letterSpacing: {
      tighter: '-0.05em',
      tight: '-0.025em',
      normal: '0',
      wide: '0.025em',
      wider: '0.05em',
      widest: '0.1em',
    },
    width: theme => ({
      auto: 'auto',
      ...theme('spacing'),
      '1/2': '50%',
      '1/3': '33.333333%',
      '2/3': '66.666667%',
      '1/4': '25%',
      '2/4': '50%',
      '3/4': '75%',
      '1/5': '20%',
      '2/5': '40%',
      '3/5': '60%',
      '4/5': '80%',
      '1/6': '16.666667%',
      '2/6': '33.333333%',
      '3/6': '50%',
      '4/6': '66.666667%',
      '5/6': '83.333333%',
      '1/12': '8.333333%',
      '2/12': '16.666667%',
      '3/12': '25%',
      '4/12': '33.333333%',
      '5/12': '41.666667%',
      '6/12': '50%',
      '7/12': '58.333333%',
      '8/12': '66.666667%',
      '9/12': '75%',
      '10/12': '83.333333%',
      '11/12': '91.666667%',
      full: '100%',
      screen: '100vw',
    }),
    height: theme => ({
      auto: 'auto',
      ...theme('spacing'),
      full: '100%',
      screen: '100vh',
      'screen-real': 'calc(100vh - var(--navbarHeight) - var(--adminbarHeight))'
    }),
    minWidth: {
      '0': '0',
      full: '100%',
    },
    minHeight: {
      '0': '0',
      full: '100%',
      screen: '100vh',
      'screen-real': 'calc(100vh - var(--navbarHeight) - var(--adminbarHeight))'
    },
    padding: theme => theme('spacing'),
    margin: (theme, { negative }) => ({
      auto: 'auto',
      ...theme('spacing'),
      ...negative(theme('spacing')),
    }),
    boxShadow: {
      default: '0 12px 32px 0 rgba(0,0,0,0.1)',
      md: '0 2px 6px 0 rgba(0,0,0,0.12);',
      lg: '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
      xl: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
      '2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
      inner: 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
      outline: '0 0 0 3px rgba(66, 153, 225, 0.5)',
      none: 'none',
    },
    container: {
      center: true,
      padding: '30px'
    }
  },
  variants: {
    padding: ['responsive', 'first', 'last'],
    margin: ['responsive', 'first', 'last'],
  }
}
