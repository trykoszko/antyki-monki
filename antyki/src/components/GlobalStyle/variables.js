export const theme = {
  colors: {
    lgrey: '#ededed',
    accent: '#d4213d',
    blue: '#09a6e4',
    grey: '#aaaaaa',
    brown: '#603414',
    golden: 'rgb(178,138,94)',
  },
  s: (n = 1) => `${n * 10}px`,
  breakpoints: {
    mobile: '768px',
    tablet: '1024px',
    desktop: '1400px',
  },
  rwd: (breakpoint, isMax = false) =>
    `(${isMax ? 'max' : 'min'}-width: ${theme.breakpoints[breakpoint]})`,
}

export default theme
