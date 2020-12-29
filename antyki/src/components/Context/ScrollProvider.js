import React, {useState, useEffect} from 'react'
import ScrollContext from './ScrollContext'

const ScrollProvider = ({children}) => {
  // determined if page has scrolled and if the view is on mobile
  const [isHeaderScrolled, toggleIsHeaderScrolled] = useState(false)
  const [isSearchOpen, toggleIsSearchOpen] = useState(false)

  // change state on scroll
  useEffect(() => {
    const handleScroll = () => {
      const isScrolled = window.scrollY > 0
      if (isScrolled !== isHeaderScrolled) {
        toggleIsHeaderScrolled(!isHeaderScrolled)
      }
    }

    document.addEventListener('scroll', handleScroll, {passive: true})

    return () => {
      // clean up the event handler when the component unmounts
      document.removeEventListener('scroll', handleScroll)
    }
  }, [isHeaderScrolled])

  return (
    <ScrollContext.Provider
      value={{
        isHeaderScrolled,
        toggleIsHeaderScrolled,
        isSearchOpen,
        toggleIsSearchOpen,
      }}
    >
      {children}
    </ScrollContext.Provider>
  )
}

export default ScrollProvider
