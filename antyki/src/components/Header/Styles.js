import React, {useContext} from 'react'
import styled from 'styled-components'

import {Icon} from 'semantic-ui-react'

import ScrollContext from '../Context/ScrollContext'

export const StyledMenu = styled.ul`
  background: blue;
`

export const StyledMenuItem = styled.li`
  background: blue;
`

const StyledSearchButton = styled.button``

export const SearchButton = () => {
  let {isSearchOpen} = useContext(ScrollContext)

  const toggleSearch = () => {
    isSearchOpen = !isSearchOpen
  }

  return (
    <StyledSearchButton onClick={toggleSearch}>
      <Icon name="search" fitted />
    </StyledSearchButton>
  )
}
