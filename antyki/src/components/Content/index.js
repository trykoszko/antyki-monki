import React from 'react'
import styled from 'styled-components'

const StyledContent = styled.div`
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;

  width: 100%;

  line-height: 1.5;

  h1,
  h2,
  h3,
  h4,
  h5,
  h6,
  p,
  ul,
  ol {
    margin-bottom: 20px;
  }
`

const Content = ({children, narrow}) => (
  <StyledContent narrow={narrow}>{children}</StyledContent>
)

export default Content
