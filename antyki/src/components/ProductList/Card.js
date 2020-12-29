import React from 'react'
import styled from 'styled-components'
import Image from 'gatsby-image'
import {Link} from 'gatsby'
import {theme} from '../GlobalStyle/variables'

const StyledCard = styled.li``

const StyledCardContainer = styled.div`
  margin: 0;
  padding: 10px;
`

const StyledCardGallery = styled.div``

const StyledCardTitle = styled.h2``

const StyledCardCategories = styled.ul`
  margin: 0 0 ${theme.s()};
  margin-bottom: 0;

  font-size: font-size(med);

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  flex-wrap: wrap;

  &:after {
    display: inline-block;

    width: ${theme.s};
    height: ${theme.s};

    margin-left: ${theme.s(0.5)};
    margin-right: ${theme.s(0.5)};

    background-color: ${theme.colors.golden};
  }
`

const StyledCardPrice = styled.p`
  display: inline;

  padding: ${theme.s(0.5)};

  background-color: ${theme.colors.golden};
  color: white;
`

const Card = ({product}) => {
  const {
    /* eslint-disable-next-line */
    wordpress_id,
    slug,
    title,
    categories,
    acf: {
      /* eslint-disable-next-line */
      olx_attributes: {price},
      /* eslint-disable-next-line */
      product_gallery,
    },
  } = product.node
  console.log(product.node)
  return (
    <StyledCard>
      <StyledCardContainer>
        <Link to={`/${slug}`}>
          <StyledCardGallery>
            {/* eslint-disable-next-line */
            product_gallery &&
            product_gallery[0] &&
            product_gallery[0].localFile ? (
              <Image
                /* eslint-disable-next-line */
                fluid={product_gallery[0].localFile.childImageSharp.fluid}
              />
            ) : (
              ''
            )}
          </StyledCardGallery>
        </Link>
        <Link to={`/${slug}`}>
          <StyledCardTitle>{title}</StyledCardTitle>
        </Link>
        {categories ? (
          <StyledCardCategories>
            {categories.map(cat => (
              <li key={cat.slug}>
                <Link to={`/kategoria/${cat.slug}`}>{cat.name}</Link>
              </li>
            ))}
          </StyledCardCategories>
        ) : (
          ''
        )}
        <StyledCardPrice>{price},-</StyledCardPrice>
      </StyledCardContainer>
    </StyledCard>
  )
}

export default Card
