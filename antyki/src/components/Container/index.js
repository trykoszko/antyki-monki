import React from 'react'
import styled from 'styled-components'

const StyledContainer = styled.div`
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;

  width: 100%;
  max-width: 1200px;

  margin: 0 auto;
  padding: 0 20px;
`

const Container = ({children}) => <StyledContainer>{children}</StyledContainer>

export default Container
