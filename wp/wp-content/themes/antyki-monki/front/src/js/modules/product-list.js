const {
  apiUrl
} = wpData

const {
  __
} = wp.i18n

window.antykiState = {
  i: 0
}

// const getFromState = prop => window.antykiState[prop]
// const setState = (prop, val) => {
//   window.antykiState[prop] = val
// }

// const getLoader = () => `<div class="c-loader">${__('Ładowanie...', 'antyki')}</div>`

// const figureTemplate = ({ orientation, name, objectFit, src, alt }) => `
//   <figure class="c-card-gallery__img c-card-gallery__img--${orientation} c-card-gallery__img--${name}">
//     <img class="c-img o-object-fit-${objectFit}" src="${src}" alt="${alt}" />
//   </figure>
// `

// const generateImages = productGallery => {
//   let html = ''

//   const firstImg = productGallery[0]['sizes']['medium']
//   const secondImg = productGallery[1]['sizes']['medium']

//   if (firstImg) {
//     const firstImgAspectRatio = Math.round(productGallery[0]['sizes']['medium-width'] / productGallery[0]['sizes']['medium-height'])
//     const firstImgObj = {
//       exists: true,
//       name: 'first',
//       aspectRatio: firstImgAspectRatio,
//       objectFit: firstImgAspectRatio === 1.78 || firstImgAspectRatio === 0.56 ? 'contain' : 'cover',
//       orientation: firstImgAspectRatio > 1 ? 'landscape' : 'portrait',
//       src: firstImg,
//       alt: productGallery[0]['alt']
//     }
//     html += figureTemplate(firstImgObj)
//   }

//   if (secondImg) {
//     const secondImgAspectRatio = Math.round(productGallery[1]['sizes']['medium-width'] / productGallery[1]['sizes']['medium-height'])
//     const secondImgObj = {
//       exists: true,
//       name: 'second',
//       aspectRatio: secondImgAspectRatio,
//       objectFit: secondImgAspectRatio === 1.78 || secondImgAspectRatio === 0.56 ? 'contain' : 'cover',
//       orientation: secondImgAspectRatio > 1 ? 'landscape' : 'portrait',
//       src: secondImg,
//       alt: productGallery[1]['alt']
//     }
//     html += figureTemplate(secondImgObj)
//   }

//   return html
// }

// const cardTemplate = product => {
//   const {
//     id,
//     productGallery,
//     url,
//     title,
//     categories,
//     price
//   } = product
//   return `
//     <div class="c-card" data-product-id="${id}">
//       <div class="c-card__inside">
//         ${productGallery ? `
//           <div class="c-card-gallery">
//             <a href="${url}">
//               ${generateImages(productGallery)}
//             </a>
//           </div>
//         ` : ''}
//           <div class="c-card__body">
//               <a href="${url}">
//                   <h4 class="c-card__title">
//                       ${title}
//                   </h4>
//               </a>
//               ${categories ? `<p class="c-card__categories">${categories.map((cat, index) => `
//                 <a class="c-link" href="${cat.url}">
//                   <span class="c-label">
//                     ${cat.name}
//                   </span>
//                 </a>
//                 ${index < categories.length - 1 ? `
//                   <span class="c-decor"></span>
//                 ` : ''}
//               `).join('')}</p>` : ''}
//               ${price ? `
//                 <p class="c-card__price">
//                   ${price},-
//                 </p>
//               ` : ''}
//           </div>
//       </div>
//     </div>
//   `
// }

// const getProducts = async () => {
//   const $productList = $('.js-product-list')
//   if ($productList.length) {
//     const i = getFromState('i')
//     if (i === 0) {
//       $productList.html(getLoader())
//     }
//     let query = ''
//     if (i === 0) {
//       query += `?per_page=12`
//     }
//     let productRequest = await fetch(`${apiUrl}products${query}`)
//     const res = await productRequest.json()
//     const products = res.data.products
//     const productsHtml = products
//       .map(product => cardTemplate(product))
//       .join('')

//     if (i === 0) {
//       $productList.html('')
//     }
//     $productList.html($productList.html() + productsHtml)
//     setState('i', i + 1)
//   }
// }

// $('document').ready(() => {
//   getProducts()
// })
