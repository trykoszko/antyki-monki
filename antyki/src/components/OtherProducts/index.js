import React from 'react'
import styled, {css} from 'styled-components'
// import Image from 'gatsby-image'
import {Link} from 'gatsby'

import {theme} from '../GlobalStyle/variables'

const StyledOtherProductsTitle = styled.h2`
  margin-bottom: 20px;

  @media ${theme.rwd('desktop')} {
    padding-top: 60px;
    margin-bottom: 40px;

    text-align: center;
  }
`

const StyledOtherProductsWrapper = styled.div`
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;

  margin-bottom: 40px;

  @media ${theme.rwd('desktop')} {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;

    margin-bottom: 80px;
  }
`

const StyledProductWrapper = styled(Link)`
  flex: 0 1 calc((100% / 2) - 40px);

  position: relative;

  display: flex;
  flex-direction: row;
  align-items: center;

  &:nth-child(2) {
    justify-content: flex-end;
  }
`

const StyledProduct = styled.div`
  width: 95%;

  display: flex;
  flex-direction: column;
  align-items: flex-start;
  text-align: left;

  ${props =>
    props.rightAligned
      ? css`
          align-items: flex-end;
          text-align: right;
        `
      : ''}
`

const StyledProductTitle = styled.h3``

const StyledLabel = styled.span`
  margin-bottom: 10px;
`

const OtherProducts = ({products}) => {
  return (
    <>
      <StyledOtherProductsTitle>Zobacz też</StyledOtherProductsTitle>
      <StyledOtherProductsWrapper>
        {products && products[0] && (
          <StyledProductWrapper to={`/${products[0].slug}`}>
            <StyledProduct rightAligned>
              <StyledLabel>Poprzedni</StyledLabel>
              <StyledProductTitle
                dangerouslySetInnerHTML={{__html: products[0].title}}
              />
            </StyledProduct>
          </StyledProductWrapper>
        )}
        {products && products[1] && (
          <StyledProductWrapper to={`/${products[1].slug}`}>
            <StyledProduct>
              <StyledLabel>Następny</StyledLabel>
              <StyledProductTitle
                dangerouslySetInnerHTML={{__html: products[1].title}}
              />
            </StyledProduct>
          </StyledProductWrapper>
        )}
      </StyledOtherProductsWrapper>
    </>
  )
}

export default OtherProducts
