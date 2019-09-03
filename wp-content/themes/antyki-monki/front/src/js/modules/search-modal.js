const SearchModal = {
  init: function () {
    this.openOnClick()
  },
  openOnClick: function () {
    const toggler = document.querySelector('.js-toggle-search')
    const modal = document.querySelector('.js-search-modal')
    if (toggler && modal) {
      const modalClose = modal.querySelector('.js-close-search')
      toggler.addEventListener('click', e => {
        modal.classList.add('is-active')
      })
      modalClose.addEventListener('click', e => {
        modal.classList.remove('is-active')
      })
    }
  }
}

SearchModal.init()
