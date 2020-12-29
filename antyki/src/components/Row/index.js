import React from 'react'
import styled from 'styled-components'

const StyledRow = styled.div`
  width: 100%;

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
`

const Row = ({children}) => <StyledRow>{children}</StyledRow>

export default Row
