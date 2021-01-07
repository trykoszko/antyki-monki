/* eslint-disable camelcase */
import React from 'react'
import styled from 'styled-components'
import {Link} from 'gatsby'

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

const StyledCatList = styled.ul`
  margin: 0;
  margin-top: ${theme.s(2)};
  margin-bottom: ${theme.s(3)};

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  flex-wrap: wrap;

  font-size: 1.3em;
  line-height: 1.8;

  @media ${theme.rwd('desktop')} {
    margin-top: ${theme.s(1.2)};

    font-size: 1.1em;
    line-height: 1.5;
  }

  &:after {
    display: inline-block;

    width: ${theme.s()};
    height: ${theme.s()};

    margin-left: ${theme.s(0.5)};
    margin-right: ${theme.s(0.5)};

    background-color: ${theme.colors.golden};
  }

  li:not(:last-child) {
    margin-right: ${theme.s()};
    padding-right: ${theme.s()};

    position: relative;

    &:after {
      content: '';
      width: ${theme.s(0.3)};
      height: ${theme.s(0.3)};

      background: ${theme.colors.golden};

      position: absolute;

      top: calc(50% - 1.5px);
      right: -1.5px;
    }
  }
`

const ProductList = ({products, categories}) => {
  return (
    <StyledProductListWrapper>
      <StyledTitle>Nasze produkty</StyledTitle>
      <StyledCatList>
        {categories.map(
          ({node}) =>
            node.count > 0 && (
              <li key={node.slug}>
                <Link to={`/kategoria/${node.slug}`}>{node.name}</Link>
              </li>
            ),
        )}
      </StyledCatList>
      <StyledProductList>
        {products.map(product => (
          <Card key={product.slug} product={product} />
        ))}
      </StyledProductList>
    </StyledProductListWrapper>
  )
}

export default ProductList
