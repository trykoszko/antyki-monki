const Promise = require('bluebird')
const path = require('path')

exports.createPages = ({graphql, actions}) => {
  const {createPage} = actions

  return new Promise((resolve, reject) => {
    const productPageTemplate = path.resolve('src/templates/ProductPage.js')
    const categoryPageTemplate = path.resolve('src/templates/CategoryPage.js')
    resolve(
      graphql(
        `
          {
            products: allWordpressWpAntyki {
              edges {
                node {
                  wordpress_id
                  slug
                }
                next {
                  wordpress_id
                }
                previous {
                  wordpress_id
                }
              }
            }
            categories: allWordpressCategory {
              edges {
                node {
                  wordpress_id
                  wordpress_parent
                  name
                  slug
                }
              }
            }
          }
        `,
      ).then(result => {
        if (result.errors) {
          console.log(result.errors)
          reject(result.errors)
        }
        const {products, categories} = result.data
        products.edges.forEach(edge => {
          createPage({
            path: `/${edge.node.slug}/`,
            component: productPageTemplate,
            context: {
              id: edge.node.wordpress_id,
              previousId: edge.previous && edge.previous.wordpress_id,
              nextId: edge.next && edge.next.wordpress_id,
            },
          })
        })
        categories.edges.forEach(edge => {
          createPage({
            path: `/kategoria/${edge.node.slug}/`,
            component: categoryPageTemplate,
            context: {
              id: edge.node.wordpress_id,
            },
          })
        })
      }),
    )
  })
}

exports.onCreateWebpackConfig = ({actions}) => {
  actions.setWebpackConfig({
    node: {fs: 'empty'},
  })
}
