import React from 'react'
import styled from 'styled-components'
import {graphql, useStaticQuery, Link} from 'gatsby'
import get from 'lodash/get'

const StyledNav = styled.ul`
  margin: 0;
  padding: 0;

  list-style: none;
`

const Nav = () => {
  const data = useStaticQuery(graphql`
    query MyQuery {
      allWordpressPage {
        edges {
          node {
            title
            slug
          }
        }
      }
    }
  `)
  const items = get(data, 'allWordpressPage.edges')
  return (
    <StyledNav>
      <li>
        <Link to="/">Strona główna</Link>
      </li>
      {items.map(item => (
        <li key={get(item, 'node.slug')}>
          <Link to={`/${get(item, 'node.slug')}`}>
            {get(item, 'node.title')}
          </Link>
        </li>
      ))}
    </StyledNav>
  )
}

export default Nav
