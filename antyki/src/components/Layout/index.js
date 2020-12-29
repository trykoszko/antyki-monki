import React from 'react'
import Headroom from 'react-headroom'
import Container from '../Container'
import Row from '../Row'
import Footer from '../Footer'
import Header from '../Header'
import GlobalStyle from '../GlobalStyle'

const Layout = ({location, children}) => {
  return (
    <>
      <GlobalStyle />
      <Headroom
        upTolerance={10}
        downTolerance={10}
        style={{zIndex: '20', height: '130px'}}
      >
        <Header location={location} />
      </Headroom>
      <Container>
        <Row>{children}</Row>
      </Container>
      <Footer />
    </>
  )
}

export default Layout
