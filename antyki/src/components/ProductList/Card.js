import React from 'react'
import styled from 'styled-components'
import Image from 'gatsby-image'
import {Link} from 'gatsby'
import {theme} from '../GlobalStyle/variables'

const StyledCard = styled.li``

const StyledCardContainer = styled.div`
  margin: 0;
  margin-bottom: ${theme.s(2)};

  padding: ${theme.s()};

  @media ${theme.rwd('desktop')} {
    margin-bottom: ${theme.s(4)};
  }
`

const StyledCardGallery = styled.div`
  width: 100%;
`

const StyledCardTitle = styled.h4`
  font-family: ${theme.fonts.serif};
  font-size: 1.6em;
  line-height: 1.1;

  margin-top: ${theme.s()};

  color: ${theme.colors.brown};
`

const StyledCardCategories = styled.ul`
  margin: 0;
  margin-top: ${theme.s(2)};

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  flex-wrap: wrap;

  font-size: 1em;

  @media ${theme.rwd('desktop')} {
    margin-top: ${theme.s(1.2)};
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

const StyledCardPrice = styled.p`
  display: inline-block;

  margin: 0;
  margin-top: ${theme.s(2)};

  padding: ${theme.s(0.5)};

  background-color: ${theme.colors.golden};
  color: white;

  @media ${theme.rwd('desktop')} {
    margin-top: ${theme.s(1.2)};
  }
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
  // console.log(product.node)
  return (
    <StyledCard>
      <StyledCardContainer>
        <StyledCardGallery>
          <Link to={`/${slug}`}>
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
          </Link>
        </StyledCardGallery>
        <Link to={`/${slug}`}>
          <StyledCardTitle
            dangerouslySetInnerHTML={{
              __html: title,
            }}
          />
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
