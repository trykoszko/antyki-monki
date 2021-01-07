import React from 'react'

const ScrollContext = React.createContext({
  isHeaderScrolled: false,
  toggleIsHeaderScrolled: () => {},
  isSearchOpen: false,
  toggleIsSearchOpen: () => {},
})

export default ScrollContext
