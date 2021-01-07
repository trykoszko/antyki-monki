import React from 'react'
import styled from 'styled-components'

import {theme} from '../GlobalStyle/variables'

const StyledRow = styled.div`
  width: 100%;

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;

  @media ${theme.rwd('desktop')} {
    justify-content: space-between;
  }
`

const Row = ({children}) => <StyledRow>{children}</StyledRow>

export default Row
