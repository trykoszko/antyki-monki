import React from 'react'
import styled from 'styled-components'
import {Link} from 'gatsby'

import Container from '../Container'
import Row from '../Row'

import Logo from './Logo'
import Nav from './Nav'

// import {SearchButton} from './Styles'

import {theme} from '../GlobalStyle/variables'

const StyledHeader = styled.div`
  background: white;

  padding-top: 20px;
  padding-bottom: 40px;

  @media ${theme.rwd('desktop')} {
    padding-bottom: 20px;
  }
`

const Header = ({location}) => (
  <StyledHeader>
    <Container>
      <Row columnOnMobile>
        <Link to="/">
          <Logo />
        </Link>
        <Nav location={location} />
      </Row>
    </Container>
  </StyledHeader>
)

export default Header
