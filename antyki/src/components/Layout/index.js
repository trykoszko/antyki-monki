import React from 'react'
import styled from 'styled-components'
import {Helmet} from 'react-helmet'

import GlobalStyle from '../GlobalStyle'

import Footer from '../Footer'
import Header from '../Header'
import ScrollToTop from './ScrollToTop'

const StyledMain = styled.main``

const StyledHeader = styled.header`
  width: 100%;
`

const Layout = ({location, children}) => (
  <>
    <GlobalStyle />
    <Helmet>
      <meta charSet="utf-8" />
      <link rel="canonical" href="https://antyki-monki.pl" />
    </Helmet>
    <StyledHeader>
      <Header location={location} />
    </StyledHeader>
    <StyledMain>{children}</StyledMain>
    <Footer />
    <ScrollToTop />
  </>
)

export default Layout
