import React from 'react'
import styled, {css} from 'styled-components'

const StyledContainer = styled.div`
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;

  width: 100%;

  margin: 0 auto;
  padding: 0 20px;

  max-width: ${props => (props.narrow ? '960px' : '1200px')};

  ${props =>
    props.withBottomOffset
      ? css`
          margin-bottom: 100px;
        `
      : ''}
`

const Container = ({children, narrow, withBottomOffset}) => (
  <StyledContainer narrow={narrow} withBottomOffset={withBottomOffset}>
    {children}
  </StyledContainer>
)

export default Container
