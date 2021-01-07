import React from 'react'
import AuthProvider from './src/components/Context/AuthProvider'
import CartProvider from './src/components/Context/CartProvider'
import ScrollProvider from './src/components/Context/ScrollProvider'

// eslint-disable-next-line import/prefer-default-export
export default ({element}) => (
  <AuthProvider>
    <CartProvider>
      <ScrollProvider>{element}</ScrollProvider>
    </CartProvider>
  </AuthProvider>
)
