import React from 'react'
import styled, {css} from 'styled-components'

import {theme} from '../GlobalStyle/variables'

const StyledRow = styled.div`
  width: 100%;

  display: flex;
  align-items: center;
  justify-content: center;

  ${(props) =>
    props.columnOnMobile
      ? css`
          flex-direction: column;
        `
      : css`
          flex-direction: row;
        `}

  @media ${theme.rwd('desktop')} {
    justify-content: space-between;

    ${(props) =>
      props.columnOnMobile
        ? css`
            flex-direction: row;
          `
        : css`
            flex-direction: row;
          `}
  }
`

const Row = ({children, columnOnMobile}) => (
  <StyledRow columnOnMobile={columnOnMobile}>{children}</StyledRow>
)

export default Row
