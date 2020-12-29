import {createGlobalStyle} from 'styled-components'
import reset from 'styled-reset'

import {theme} from './variables'

const GlobalStyle = createGlobalStyle`
  ${reset}

  body {
    margin: 0;
    padding: 0;

    font-family: Open-Sans, Helvetica, Sans-Serif;
  }

  a {
    color: ${theme.colors.brown};

    &:visited,
    &:focus,
    &:hover {
      color: ${theme.colors.brown};
    }
    &:hover {
      text-decoration: underline;
    }
  }
`

export default GlobalStyle
