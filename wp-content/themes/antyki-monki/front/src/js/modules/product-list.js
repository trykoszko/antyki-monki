import $ from 'jquery'

let iteration = 0

const figureTemplate = img => {
  let orientation = ''
  let objectFit = ''
  const {
    name,
    src,
    alt
  } = img
  return `
    <figure class="c-card-gallery__img c-card-gallery__img--${orientation} c-card-gallery__img--${name}">
      <img class="c-img o-object-fit-${objectFit}" src="${src}" alt="${alt}" />
    </figure>
  `
}

const cardTemplate = async ({ postId, productGallery, postLink, postTitle, postCategories, postPrice }) => {
  const postCardImages = await $.ajax({
    'method': 'GET',
    'url': `${wpData.apiUrl}?action=get_card_images`
  })
  console.log('postCardImages', postCardImages)

  return `
    <div class="c-card">
      <div class="c-card__inside">
        ${productGallery ? `
          <div class="c-card-gallery">
            <a href="${postLink}">
                ${postCardImages}
            </a>
          </div>
        ` : ''}
          <div class="c-card__body">
              <a href="${postLink}">
                  <h4 class="c-card__title">
                      ${postTitle}
                  </h4>
              </a>
              ${postCategories ? `<p class="c-card__categories">${postCategories.map((cat, i) => `
                <a class="c-link" href="${cat.link}">
                  <span class="c-label">
                    ${cat.name}
                  </span>
                </a>
                ${i < postCategories.length ? `
                  <span class="c-decor"></span>
                ` : ''}
              `)}</p>` : ''}
              ${postPrice ? `
                <p class="c-card__price">
                  ${postPrice},-
                </p>
              ` : ''}
          </div>
      </div>
    </div>
  `
}

const getData = (action, page, categories, meta, priceFrom, priceTo) => {
  $.ajax({
    'method': 'GET',
    'url': `${wpData.apiUrl}?action=${action}`
    // 'dataType': 'jsonp',
  })
    .done(res => {
      console.log('res', res)
      // @todo map data to component
      // @todo add pagination, search param etc
      if (iteration === 0) {
        $('.js-product-list').html(res.data.map(post => ``).join(''))
      } else {
        $('.js-product-list').html($('.js-product-list').html() + res.data.map(post => ``).join(''))
      }
      iteration++
    })
    .fail(err => {
      console.log('err', err)
    })
}

getData('get_all_posts', 0)
