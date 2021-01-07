import React, {useContext} from 'react'
import styled from 'styled-components'

import ScrollContext from '../Context/ScrollContext'

import {theme} from '../GlobalStyle/variables'

const StyledScrollTopBtn = styled.button`
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;

  color: ${theme.colors.golden};

  position: fixed;
  top: 30px;

  width: 60px;
  height: 60px;

  background: white;

  box-shadow: 0px 0px 30px -10px rgba(0, 0, 0, 0.4);

  cursor: pointer;

  border: none;
  border-radius: 100%;

  transition: all 0.3s;

  ${props => `right: ${props.isVisible ? 20 : -300}px;`}

  z-index: 900;

  @media ${theme.rwd('desktop')} {
    width: 60px;
    height: 60px;

    ${props => `right: ${props.isVisible ? 20 : -300}px;`}
  }

  svg {
    fill: ${theme.colors.golden};

    width: 30px;
    height: 30px;
  }
`

const ScrollToTop = () => {
  const {scrollTop} = useContext(ScrollContext)

  return (
    <StyledScrollTopBtn
      isVisible={scrollTop > 300}
      onClick={() => {
        window.scrollTo(0, 0)
      }}
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 492 492">
        <defs />
        <path d="M442.627 185.388L265.083 7.844C260.019 2.78 253.263 0 245.915 0c-7.204 0-13.956 2.78-19.02 7.844L49.347 185.388c-10.488 10.492-10.488 27.568 0 38.052l16.12 16.128c5.064 5.06 11.82 7.844 19.028 7.844 7.204 0 14.192-2.784 19.252-7.844l103.808-103.584v329.084c0 14.832 11.616 26.932 26.448 26.932h22.8c14.832 0 27.624-12.1 27.624-26.932V134.816l104.396 104.752c5.06 5.06 11.636 7.844 18.844 7.844s13.864-2.784 18.932-7.844l16.072-16.128c10.492-10.488 10.452-27.56-.044-38.052z" />
      </svg>
    </StyledScrollTopBtn>
  )
}

export default ScrollToTop
