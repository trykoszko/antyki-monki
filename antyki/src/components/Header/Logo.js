import React, {useContext} from 'react'

import {Image} from 'semantic-ui-react'

import ScrollContext from '../Context/ScrollContext'

import antyki from '../../images/antyki-logo.png'

const Logo = () => {
  const {isHeaderScrolled} = useContext(ScrollContext)

  return (
    <Image
      src={antyki}
      style={{
        marginRight: '1.5em',
        maxWidth: isHeaderScrolled ? '80px' : '120px',
        transition: 'all .2s',
      }}
      alt="Antyki Mońki"
    />
  )
}

export default Logo
