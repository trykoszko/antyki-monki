import React from 'react'
import {graphql, useStaticQuery} from 'gatsby'
import get from 'lodash/get'
import ProductList from '../components/ProductList'
import SEO from '../components/SEO'
import Layout from '../components/Layout'
import Hero from '../components/Hero'

const PageIndex = props => {
  const data = useStaticQuery(graphql`
    query IndexQuery {
      site {
        siteMetadata {
          title
        }
      }
      allWordpressWpAntyki {
        edges {
          node {
            wordpress_id
            title
            slug
            categories {
              name
              slug
            }
            acf {
              olx_attributes {
                price
              }
              product_gallery {
                alt_text
                localFile {
                  childImageSharp {
                    fluid(
                      maxWidth: 400
                      maxHeight: 400
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
      allWordpressCategory {
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
    }
  `)

  const {location} = props

  const siteTitle = get(data, 'site.siteMetadata.title')
  const products = get(data, 'allWordpressWpAntyki.edges')
  const categories = get(data, 'allWordpressCategory.edges')
  return (
    <Layout location={location}>
      <SEO title={siteTitle} />
      <Hero />
      <ProductList products={products} categories={categories} />
    </Layout>
  )
}

export default PageIndex
