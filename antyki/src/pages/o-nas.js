import React from 'react'
import {graphql, useStaticQuery} from 'gatsby'
import get from 'lodash/get'

import SEO from '../components/SEO'
import Layout from '../components/Layout'
import Container from '../components/Container'
import Content from '../components/Content'

const Onas = ({location}) => {
  const data = useStaticQuery(graphql`
    query AboutusQuery {
      allWordpressPage(filter: {slug: {eq: "o-nas"}}) {
        edges {
          node {
            title
            content
          }
        }
      }
    }
  `)

  // console.log('data', data)

  const title = get(data, 'allWordpressPage.edges[0].node.title')
  const content = get(data, 'allWordpressPage.edges[0].node.content')

  return (
    <Layout location={location}>
      <SEO title={title} />
      <Container narrow withBottomOffset withTopOffset>
        <Content>
          <h1>{title}</h1>
          <div dangerouslySetInnerHTML={{__html: content}} />
        </Content>
      </Container>
    </Layout>
  )
}

export default Onas
