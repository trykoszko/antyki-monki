import React from 'react'
import styled, {css} from 'styled-components'
import {Link} from 'gatsby'

import {Responsive} from 'semantic-ui-react'

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

  /* ${props =>
    props.scrolled
      ? css`
          box-shadow: 0px 0px 30px -10px rgba(0, 0, 0, 0.16);
        `
      : ''} */

  @media ${theme.rwd('desktop')} {
    padding-bottom: 20px;
  }
`

const Header = ({location}) => {
  return (
    <StyledHeader>
      <Responsive maxWidth={Responsive.onlyMobile.maxWidth}>
        <Container>
          <Row>
            <Link to="/">
              <Logo />
            </Link>
          </Row>
          <Row>
            <Nav location={location} />
          </Row>
          {/* <Row>
            <SearchButton />
          </Row> */}
        </Container>
      </Responsive>
      <Responsive minWidth={Responsive.onlyTablet.minWidth}>
        <Container>
          <Row>
            <Link to="/">
              <Logo />
            </Link>
            <Nav location={location} />
          </Row>
        </Container>
      </Responsive>
    </StyledHeader>
  )
}

export default Header
