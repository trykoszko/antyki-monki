import Swiper from 'swiper'

const showImageModal = (url, orientation) => {
  const imageModal = document.querySelector('.js-image-modal')
  const fig = imageModal.querySelector('.js-image')
  const image = fig.querySelector('img')
  image.src = url
  fig.classList = `c-image-modal__img c-image-modal__img--${orientation} js-image`
  imageModal.classList.add('is-active')
}

const closeImageModal = () => {
  const imageModal = document.querySelector('.js-image-modal')
  const closeBtn = imageModal.querySelector('.js-close-image-modal')
  closeBtn.addEventListener('click', e => {
    imageModal.classList.remove('is-active')
  })
}

const Sliders = {
  init: function () {
    this.productGallerySlider()
  },
  productGallerySlider: function () {
    const sliderClass = '.js-product-gallery-slider'
    if (document.querySelector(sliderClass)) {
      const slider = new Swiper(sliderClass, {
        speed: 400,
        spaceBetween: 100,
        loop: true,
        navigation: {
          prevEl: '.c-product-gallery__btn--prev',
          nextEl: '.c-product-gallery__btn--next',
          hiddenClass: 'is-hidden'
        },
        pagination: {
          el: '.c-product-gallery__nav',
          type: 'bullets',
          bulletClass: 'c-product-gallery__nav-item',
          bulletActiveClass: 'is-current',
          renderBullet: (index, className) => {
            return `<li class="${className}">
                <span class="c-dot">
                </span>
            </li>`
          }
        }
      })
      const slides = document.querySelector(sliderClass).querySelectorAll('.js-slide')
      Array.from(slides).forEach(slide => {
        slide.addEventListener('click', e => {
          const fullSizeUrl = slide.dataset.fullSizeUrl
          const orientation = slide.dataset.imgOrientation
          if (fullSizeUrl.length > 0) {
            showImageModal(fullSizeUrl, orientation)
          }
        })
      })
      closeImageModal()
    }
  }
}

Sliders.init()
