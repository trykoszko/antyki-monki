/* eslint-disable */
import React from 'react'
import {graphql, Link} from 'gatsby'
import Image from 'gatsby-image'
import styled, {css} from 'styled-components'

import SEO from '../components/SEO'
import Layout from '../components/Layout'
import Container from '../components/Container'

import {theme} from '../components/GlobalStyle/variables'

const StyledCategoryWrapper = styled.div`
  display: flex;
  flex-direction: column-reverse;

  @media ${theme.rwd('desktop')} {
    flex-direction: row;
    align-items: flex-start;
    justify-content: space-between;
  }
`

const StyledCategoryListWrapper = styled.div`
  display: flex;
  flex-direction: column;

  li {
    margin-bottom: 10px;
  }

  @media ${theme.rwd('desktop')} {
    flex-direction: row;

    li {
      margin-bottom: 15px;
    }
  }
`

const StyledCategoryTitle = styled.h1`
  font-size: 1.5rem;

  margin-bottom: 20px;

  @media ${theme.rwd('desktop')} {
    margin-bottom: 40px;
  }
`

const StyledCategoryProductListWrapper = styled.div`
  li {
    width: 100%;

    display: flex;
    flex-direction: row;
    align-items: stretch;

    margin-bottom: 20px;
  }

  @media ${theme.rwd('desktop')} {
    flex-direction: row;
    flex: 0 1 75%;

    font-size: 1.25rem;
  }
`

const StyledCategoryCatListWrapper = styled.div`
  font-family: ${theme.fonts.serif};

  @media ${theme.rwd('desktop')} {
    flex-direction: row;
    flex: 0 1 25%;

    font-size: 1.1rem;
  }
`

const StyledCategory = styled(Link)`
  ${props =>
    props.current
      ? css`
          font-weight: bold;
          text-decoration: underline;
        `
      : ''}
`

const StyledProduct = styled(Link)`
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;

  width: 100%;

  border-bottom: 1px solid ${theme.colors.lgrey};
`

const StyledInfo = styled.p`
  font-size: 1.25rem;
`

const StyledProductImage = styled(Image)`
  width: 100px;
  height: 100px;

  margin-right: 20px;
`

const StyledProductLink = styled(Link)`
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  justify-content: center;
  align-items: flex-start;

  height: 100px;
`

const StyledProductTitle = styled.h4``

const StyledProductPrice = styled.p`
  font-size: 1rem;

  margin-top: 10px;
  padding: 5px;
  background-color: ${theme.colors.golden};
  color: white;
`

const CategoryPageTemplate = ({data, pageContext}) => {
  const {id} = pageContext

  // console.log('pageContext', pageContext)
  // console.log('data', data)

  const {category, allCategories, products} = data

  const title = category.name

  return (
    <Layout>
      <SEO title={title} />
      <Container withTopOffset withBottomOffset>
        <StyledCategoryWrapper>
          <StyledCategoryProductListWrapper>
            <StyledCategoryTitle>Kategoria: {title}</StyledCategoryTitle>
            {products && products.edges && products.edges.length > 0 ? (
              <ul>
                {products.edges.map(({node}, key) => (
                  <li key={key}>
                    <StyledProductLink to={`/${node.slug}`}>
                      <StyledProductImage
                        fluid={
                          node.acf.product_gallery[0].localFile.childImageSharp
                            .fluid
                        }
                      />
                      <StyledProductTitle
                        dangerouslySetInnerHTML={{__html: node.title}}
                      />
                      <StyledProductPrice>
                        {node.acf.olx_attributes.price},-
                      </StyledProductPrice>
                    </StyledProductLink>
                  </li>
                ))}
              </ul>
            ) : (
              <StyledInfo>Brak produktów w danej kategorii.</StyledInfo>
            )}
          </StyledCategoryProductListWrapper>
          <StyledCategoryCatListWrapper>
            <StyledCategoryTitle>Inne kategorie</StyledCategoryTitle>
            {allCategories && (
              <StyledCategoryListWrapper>
                <ul>
                  {allCategories.edges.map(({node}, key) => {
                    return (
                      node.count > 0 && (
                        <li key={key}>
                          <StyledCategory
                            current={
                              node.slug === category.slug ? 'true' : undefined
                            }
                            to={`/kategoria/${node.slug}`}
                          >
                            {node.name}
                          </StyledCategory>
                        </li>
                      )
                    )
                  })}
                </ul>
              </StyledCategoryListWrapper>
            )}
          </StyledCategoryCatListWrapper>
        </StyledCategoryWrapper>
      </Container>
    </Layout>
  )
}

export default CategoryPageTemplate

export const pageQuery = graphql`
  query CategoryQuery($id: Int!) {
    category: wordpressCategory(wordpress_id: {eq: $id}) {
      wordpress_id
      name
      slug
      description
    }
    allCategories: allWordpressCategory {
      edges {
        node {
          wordpress_id
          wordpress_parent
          name
          slug
          count
        }
      }
    }
    products: allWordpressWpAntyki(
      filter: {categories: {elemMatch: {wordpress_id: {eq: $id}}}}
      sort: {fields: title, order: ASC}
    ) {
      edges {
        node {
          id
          wordpress_id
          title
          slug
          acf {
            olx_attributes {
              price
            }
            product_gallery {
              localFile {
                childImageSharp {
                  fluid(
                    maxWidth: 250
                    maxHeight: 250
                    fit: CONTAIN
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
    }
  }
`
