import React from 'react'
import styled, {css} from 'styled-components'

const StyledContainer = styled.div`
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;

  width: 100%;

  margin: 0 auto;
  padding: 0 20px;

  ${props =>
    props.narrow
      ? css`
          max-width: 1100px;
        `
      : css`
          max-width: 1400px;
        `}

  ${props =>
    props.withBottomOffset
      ? css`
          margin-bottom: 100px;
        `
      : ''}
  ${props =>
    props.withTopOffset
      ? css`
          margin-top: 60px;
        `
      : ''}
`

const Container = ({children, narrow, withBottomOffset, withTopOffset}) => (
  <StyledContainer
    narrow={narrow}
    withBottomOffset={withBottomOffset}
    withTopOffset={withTopOffset}
  >
    {children}
  </StyledContainer>
)

export default Container
