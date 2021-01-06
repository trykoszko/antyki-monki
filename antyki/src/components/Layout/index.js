import React from 'react'
import Headroom from 'react-headroom'
import styled from 'styled-components'
import {Helmet} from 'react-helmet'

import Footer from '../Footer'
import Header from '../Header'
import GlobalStyle from '../GlobalStyle'
import {theme} from '../GlobalStyle/variables'

const StyledMain = styled.main`
  margin-top: 60px;

  @media ${theme.rwd('desktop')} {
    margin-top: 80px;
  }
`

const StyledHeader = styled.header`
  width: 100%;
`

const StyledHeadroom = styled(Headroom)`
  z-index: '20';
`

const Layout = ({location, children}) => (
  <>
    <GlobalStyle />
    <Helmet>
      <meta charSet="utf-8" />
      <title>My Title</title>
      <link rel="canonical" href="http://mysite.com/example" />
    </Helmet>
    <StyledHeader>
      <StyledHeadroom upTolerance={10} downTolerance={10}>
        <Header location={location} />
      </StyledHeadroom>
    </StyledHeader>
    <StyledMain>{children}</StyledMain>
    <Footer />
  </>
)

export default Layout
