import React from 'react'
import {graphql, useStaticQuery} from 'gatsby'
import styled from 'styled-components'
import get from 'lodash/get'
import Image from 'gatsby-image'

import {theme} from '../GlobalStyle/variables'

import Container from '../Container'

const StyledHeroWrapper = styled.section`
  width: 100%;
  height: 60vh;

  margin-bottom: 40px;

  padding: $s * 25 0;

  position: relative;

  @media ${theme.rwd('desktop')} {
    height: auto;
  }
`

const StyledCardWrapper = styled.div`
  height: 100%;
`

const StyledImageWrapper = styled.div`
  position: relative;

  width: 100%;
  height: 100%;

  width: 100%;
  height: 100%;

  opacity: 0.2;

  div {
    height: 100%;
  }
`

const StyledBackground = styled.div`
  background: ${theme.colors.golden};

  position: absolute;
  top: 0;
  left: 0;

  width: 100%;
  height: 100%;

  z-index: -1;
`

const StyledContentWrapper = styled.div`
  position: absolute;
  top: 0;
  left: 0;

  width: 100%;
  height: 100%;

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;

  z-index: 10;

  h1 {
    margin-bottom: 20px;
  }
  p {
    font-size: 1.1rem;
    line-height: 1.5;
  }
`

const Hero = () => {
  const data = useStaticQuery(graphql`
    query OptionsQuery_1 {
      wordpressAcfOptions {
        options {
          hero_image {
            localFile {
              childImageSharp {
                fluid(
                  maxWidth: 1400
                  maxHeight: 600
                  fit: COVER
                  background: "#ededed"
                ) {
                  ...GatsbyImageSharpFluid_withWebp_tracedSVG
                }
              }
            }
          }
        }
      }
    }
  `)

  const image = get(data, 'wordpressAcfOptions.options.hero_image.localFile')

  return (
    <StyledHeroWrapper>
      {image && image.childImageSharp.fluid ? (
        <StyledCardWrapper>
          <StyledImageWrapper>
            <Image fluid={image.childImageSharp.fluid} />
          </StyledImageWrapper>
          <StyledBackground />
          <StyledContentWrapper>
            <Container>
              <h1>Antyki Mońki</h1>
              <p>
                Oferujemy antyki i starocie takie jak meble, obrazy, ozdoby,
                artykuły mosiężne czy żyrandole.
                <br />
                Nasze produkty pochodzą głównie z z Niemiec oraz Belgii.
              </p>
            </Container>
          </StyledContentWrapper>
        </StyledCardWrapper>
      ) : (
        ''
      )}
    </StyledHeroWrapper>
  )
}

export default Hero
