/* eslint-disable camelcase */
import React from 'react'
import styled from 'styled-components'

import {theme} from '../GlobalStyle/variables'

import Card from './Card'

const StyledProductListWrapper = styled.div`
  margin: 0 auto;

  padding: 0 20px;

  width: 100%;
  max-width: 960px;
`

const StyledProductList = styled.ul`
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  justify-content: flex-start;
  flex-wrap: wrap;

  list-style: none;

  margin: 0 auto;
  padding: 0;

  width: 100%;

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

const StyledTitle = styled.h2`
  margin-bottom: 20px;
`

const ProductList = ({products}) => {
  return (
    <StyledProductListWrapper>
      <StyledTitle>Nasze produkty</StyledTitle>
      <StyledProductList>
        {products.map(product => (
          <Card key={`${product.slug}`} product={product} />
        ))}
      </StyledProductList>
    </StyledProductListWrapper>
  )
}

export default ProductList
