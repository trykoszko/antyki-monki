import React, {useContext} from 'react'
import styled, {css} from 'styled-components'

import {Responsive} from 'semantic-ui-react'

import Container from '../Container'
import Row from '../Row'

import Logo from './Logo'
import Nav from './Nav'

import {SearchButton} from './Styles'

import ScrollContext from '../Context/ScrollContext'

const StyledHeader = styled.div`
  background: white;

  padding-top: 20px;
  padding-bottom: 20px;

  ${props =>
    props.scrolled
      ? css`
          box-shadow: 0px 0px 30px -10px rgba(0, 0, 0, 0.16);
        `
      : ''}
`

const Header = () => {
  const {isHeaderScrolled} = useContext(ScrollContext)

  return (
    <StyledHeader scrolled={isHeaderScrolled}>
      <Responsive maxWidth={Responsive.onlyMobile.maxWidth}>
        <Container>
          <Row>
            <Logo />
          </Row>
          <Row>
            <Nav />
          </Row>
          <Row>
            <SearchButton />
          </Row>
        </Container>
      </Responsive>
      <Responsive minWidth={Responsive.onlyTablet.minWidth}>
        <Container>
          <Row>
            <Logo />
            <Nav />
          </Row>
        </Container>
      </Responsive>
    </StyledHeader>
  )
}

export default Header
