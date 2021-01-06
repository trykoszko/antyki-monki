/* eslint-disable */
import React from 'react'
import {graphql} from 'gatsby'
import SEO from '../components/SEO'
import get from 'lodash/get'
import ProductSummary from '../components/ProductSummary'
import ProductAttributes from '../components/ProductAttributes'
import Layout from '../components/Layout'
import Container from '../components/Container'
import Content from '../components/Content'

const ProductPageTemplate = ({data, pageContext}) => {
  const {id} = pageContext

  console.log('pageContext', pageContext)
  console.log('data', data)

  return (
    <Layout>
      {/* <SEO title={title} /> */}
      <Container narrow withBottomOffset>
        <Content>
          {/* <h1>{title}</h1>
          <div dangerouslySetInnerHTML={{ __html: content }} /> */}
        </Content>
      </Container>
    </Layout>
  )
}

export default ProductPageTemplate

export const pageQuery = graphql`
  query ProductQuery {
    wordpressWpAntyki(wordpress_id: {eq: 720}) {
      title
    }
  }
`
