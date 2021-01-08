/* eslint-disable */
import React from 'react'
import {graphql, Link} from 'gatsby'
import Image from 'gatsby-image'
import styled, {css} from 'styled-components'

import SEO from '../components/SEO'
import Layout from '../components/Layout'
import Container from '../components/Container'
import OtherProducts from '../components/OtherProducts'

import {theme} from '../components/GlobalStyle/variables'

const StyledProduct = styled.div`
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;

  margin-bottom: 40px;

  @media ${theme.rwd('desktop')} {
    flex-direction: row;
    justify-content: space-between;
    flex-wrap: nowrap;
  }
`

const StyledProductDesc = styled.div`
  width: 100%;

  @media ${theme.rwd('desktop')} {
    width: 25%;

    position: relative;
  }
`

const StyledProductDescWrapper = styled.div`
  @media ${theme.rwd('desktop')} {
    position: sticky;
    top: 0;
    left: 0;

    padding-top: 20px;
    padding-right: 5%;
  }
`

const StyledProductGallery = styled.div`
  width: 100%;

  @media ${theme.rwd('desktop')} {
    width: 75%;
    padding-left: 2%;
  }
`

const StyledProductGalleryImage = styled(Image)`
  &:not(:last-child) {
    margin-bottom: 20px;

    @media ${theme.rwd('desktop')} {
      margin-bottom: 40px;
    }
  }
`

const StyledGoBack = styled.a`
  margin: 0;
  padding: 0;
`

const StyledProductTitle = styled.h1`
  font-size: 2.3rem;
  line-height: 1.25;

  margin-top: 15px;

  @media ${theme.rwd('desktop')} {
  }
`

const StyledProductCategories = styled.ul`
  margin: 0;
  margin-top: 20px;

  padding: 0;

  display: flex;
  flex-direction: row;

  li {
    &:not(:last-child) {
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
  }
`

const StyledProductDescription = styled.p`
  margin-top: 20px;

  max-width: 85%;

  line-height: 1.4;
`

const StyledSubtitle = styled.h3`
  margin-top: 40px;
`

const StyledAttributeList = styled.ul`
  margin: 0;
  margin-top: 25px;

  padding: 0;

  list-style: none;
`

const StyledAttribute = styled.li`
  margin-bottom: 15px;

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;

  ${(props) =>
    props.empty &&
    css`
      height: 1px;
      width: 100%;

      background: ${theme.colors.lgrey};
    `}

  span:nth-child(2) {
    text-align: right;

    ${(props) =>
      props.golden &&
      css`
        padding: 5px;
        background-color: ${theme.colors.golden};
        color: white;
      `}
  }
`

const ProductPageTemplate = ({data, pageContext}) => {
  const {id} = pageContext

  console.log('data', data)

  const {post, nextPost, previousPost} = data

  const {
    title,
    acf,
    categories,
    acf: {product_attributes, olx_attributes},
  } = post

  const hasDimensions =
    (product_attributes.width && product_attributes.width !== '0') ||
    (product_attributes.height && product_attributes.height !== '0') ||
    (product_attributes.depth && product_attributes.depth !== '0')

  return (
    <Layout>
      <SEO title={title} />
      <Container withBottomOffset>
        <StyledProduct>
          <StyledProductDesc>
            <StyledProductDescWrapper>
              <StyledGoBack
                onClick={(e) => {
                  e.preventDefault()
                  history.back()
                }}
              >
                &laquo; Powrót
              </StyledGoBack>
              <StyledProductTitle dangerouslySetInnerHTML={{__html: title}} />
              {categories && (
                <StyledProductCategories>
                  {categories.map((cat, key) => (
                    <li key={key}>
                      <Link to={`/kategoria/${cat.slug}`}>{cat.name}</Link>
                    </li>
                  ))}
                </StyledProductCategories>
              )}
              {product_attributes && (
                <>
                  <StyledProductDescription
                    dangerouslySetInnerHTML={{__html: product_attributes.desc}}
                  />
                  <StyledSubtitle>Dane przedmiotu</StyledSubtitle>
                  <StyledAttributeList>
                    {product_attributes.width &&
                      product_attributes.width !== '0' && (
                        <StyledAttribute>
                          <span>Szerokość</span>{' '}
                          <span>{product_attributes.width}cm</span>
                        </StyledAttribute>
                      )}
                    {product_attributes.height &&
                      product_attributes.height !== '0' && (
                        <StyledAttribute>
                          <span>Wysokość</span>{' '}
                          <span>{product_attributes.height}cm</span>
                        </StyledAttribute>
                      )}
                    {product_attributes.depth &&
                      product_attributes.depth !== '0' && (
                        <StyledAttribute>
                          <span>Głębokość</span>{' '}
                          <span>{product_attributes.depth}cm</span>
                        </StyledAttribute>
                      )}
                    {hasDimensions && <StyledAttribute empty />}
                    {product_attributes.canvas_type && (
                      <StyledAttribute>
                        <span>Rodzaj płótna</span>{' '}
                        <span>{product_attributes.canvas_type.label}</span>
                      </StyledAttribute>
                    )}
                    {product_attributes.paint_type && (
                      <StyledAttribute>
                        <span>Farba</span>{' '}
                        <span>{product_attributes.paint_type.label}</span>
                      </StyledAttribute>
                    )}
                    {product_attributes.material &&
                      product_attributes.material.length > 0 && (
                        <StyledAttribute>
                          <span>Materiał</span>{' '}
                          <span>{product_attributes.material.join(', ')}</span>
                        </StyledAttribute>
                      )}
                    {product_attributes.additional &&
                      product_attributes.additional.length > 0 && (
                        <StyledAttribute>
                          <span>Inne</span>{' '}
                          <span>
                            {product_attributes.additional
                              .map((item) => item.label)
                              .join(',<br />')}
                          </span>
                        </StyledAttribute>
                      )}
                    <StyledAttribute empty />
                    {olx_attributes.price && (
                      <StyledAttribute golden>
                        <span>Cena</span> <span>{olx_attributes.price},-</span>
                      </StyledAttribute>
                    )}
                  </StyledAttributeList>
                </>
              )}
            </StyledProductDescWrapper>
          </StyledProductDesc>
          <StyledProductGallery>
            {acf.product_gallery &&
              acf.product_gallery.map((item) => (
                <StyledProductGalleryImage
                  fluid={item.localFile.childImageSharp.fluid}
                />
              ))}
          </StyledProductGallery>
        </StyledProduct>
      </Container>
      <Container narrow>
        <OtherProducts products={[previousPost, nextPost]} />
      </Container>
    </Layout>
  )
}

export default ProductPageTemplate

export const pageQuery = graphql`
  query ProductQuery($id: Int!, $nextId: Int, $previousId: Int) {
    post: wordpressWpAntyki(wordpress_id: {eq: $id}) {
      title
      status
      slug
      wordpress_id
      date(formatString: "DD/MM/YYYY")
      categories {
        slug
        wordpress_id
        name
      }
      acf {
        olx_id
        olx_created_at
        olx_olx_data
        olx_valid_to
        olx_attributes {
          state
          price_min
          price
          olx_title
          cat {
            label
            value
          }
        }
        product_attributes {
          width
          height
          depth
          desc
          material
          state {
            label
            value
          }
          paint_type {
            label
            value
          }
          canvas_type {
            label
            value
          }
          additional {
            label
            value
          }
        }
        product_gallery {
          localFile {
            childImageSharp {
              fluid(maxWidth: 1600, quality: 100) {
                ...GatsbyImageSharpFluid_withWebp_tracedSVG
              }
            }
          }
        }
      }
    }
    nextPost: wordpressWpAntyki(wordpress_id: {eq: $nextId}) {
      wordpress_id
      title
      slug
      acf {
        product_gallery {
          localFile {
            childImageSharp {
              fluid(maxWidth: 500, quality: 75) {
                ...GatsbyImageSharpFluid_withWebp_tracedSVG
              }
            }
          }
        }
      }
    }
    previousPost: wordpressWpAntyki(wordpress_id: {eq: $previousId}) {
      wordpress_id
      title
      slug
      acf {
        product_gallery {
          localFile {
            childImageSharp {
              fluid(maxWidth: 500, quality: 75) {
                ...GatsbyImageSharpFluid_withWebp_tracedSVG
              }
            }
          }
        }
      }
    }
  }
`
