import React from 'react'
import styled, {css} from 'styled-components'
import {graphql, useStaticQuery, Link} from 'gatsby'
import get from 'lodash/get'

import {theme} from '../GlobalStyle/variables'

const StyledNav = styled.ul`
  margin: 0;
  margin-top: 40px;

  padding: 0;

  list-style: none;

  font-family: ${theme.fonts.serif};

  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-end;

  font-size: 1.1rem;

  li {
    &:not(:first-child) {
      margin-left: ${theme.s(2)};
    }
  }

  @media ${theme.rwd('desktop')} {
    margin-top: 0;
  }
`

const StyledMenuLink = styled(Link)`
  ${props =>
    props.isCurrent
      ? css`
          text-decoration: underline;
        `
      : ''}
`

const Nav = ({location}) => {
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
      site {
        siteMetadata {
          siteUrl
        }
      }
    }
  `)
  const items = get(data, 'allWordpressPage.edges')

  return (
    <StyledNav>
      <li>
        <StyledMenuLink
          to="/"
          isCurrent={location && location.pathname === '/'}
        >
          Strona główna
        </StyledMenuLink>
      </li>
      {items.map(item => {
        // console.log('item', item)
        return (
          <li key={get(item, 'node.slug')}>
            <StyledMenuLink
              to={`/${get(item, 'node.slug')}`}
              isCurrent={
                location &&
                location.pathname.indexOf(get(item, 'node.slug')) !== -1
              }
            >
              {get(item, 'node.title')}
            </StyledMenuLink>
          </li>
        )
      })}
    </StyledNav>
  )
}

export default Nav
