import {createGlobalStyle} from 'styled-components'
import reset from 'styled-reset'

import {theme} from './variables'

const GlobalStyle = createGlobalStyle`
  ${reset}

  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display&family=Work+Sans&display=swap');

  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    padding: 0;

    font-family: ${theme.fonts.main};
    font-size: ${theme.fontSizes.main};
  }

  a {
    color: ${theme.colors.brown};

    text-decoration: none;

    &:visited,
    &:focus,
    &:hover {
      color: ${theme.colors.brown};
    }
    &:hover {
      text-decoration: underline;
    }
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    font-family: ${theme.fonts.serif};
  }

  h1 { font-size: 4em; }
  h2 { font-size: 3em; }
  h3 { font-size: 2.25em; }
  h4 { font-size: 1.6em; }
  h5 { font-size: 1.25em; }
  h6 { font-size: 1em; }

  @media ${theme.rwd('desktop')} {
    h1 { font-size: 3em; }
    h2 { font-size: 2em; }
    h3 { font-size: 1.5em; }
    h4 { font-size: 1.25em; }
    h5 { font-size: 1em; }
    h6 { font-size: 1em; }
  }
`

export default GlobalStyle
