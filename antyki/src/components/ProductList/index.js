/* eslint-disable camelcase */
import React from 'react'
import styled from 'styled-components'

import {theme} from '../GlobalStyle/variables'

import Card from './Card'

const StyledProductList = styled.ul`
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  justify-content: flex-start;
  flex-wrap: wrap;

  list-style: none;

  margin: 0;
  padding: 0;

  & > li {
    width: 100%;

    img {
      max-width: 100%;
      height: auto;
    }

    @media ${theme.rwd('desktop')} {
      width: 25%;
    }
  }
`

const ProductList = ({products}) => {
  return (
    <StyledProductList>
      {products.map(product => (
        <Card key={product.slug} product={product} />
      ))}
    </StyledProductList>
  )
}

export default ProductList
