import React from 'react'
import {graphql, useStaticQuery} from 'gatsby'
import get from 'lodash/get'
import ProductList from '../components/ProductList'
import SEO from '../components/SEO'
import Layout from '../components/Layout'

const StoreIndex = ({location}) => {
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
                    fluid(maxWidth: 400, maxHeight: 400) {
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
  `)

  const siteTitle = get(data, 'site.siteMetadata.title')
  const products = get(data, 'allWordpressWpAntyki.edges')
  return (
    <Layout location={location}>
      <SEO title={siteTitle} />
      <ProductList products={products} />
    </Layout>
  )
}

export default StoreIndex
