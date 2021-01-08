const Header = {
  init: function () {
    this.addClassOnScroll()
    this.toggleMobileNav()
  },
  addClassOnScroll: function () {
    const header = document.querySelector('.js-header')
    if (header) {
      window.addEventListener('scroll', e => {
        if (window.scrollY >= 1) {
          header.classList.add('is-scrolled')
        } else {
          header.classList.remove('is-scrolled')
        }
      })
    }
  },
  toggleMobileNav: function () {
    const nav = document.querySelector('.js-header-nav')
    const toggler = document.querySelector('.js-toggle-menu')
    if (nav && toggler) {
      const closer = nav.querySelector('.js-close-nav')
      toggler.addEventListener('click', e => {
        nav.classList.add('is-active')
      })
      closer.addEventListener('click', e => {
        nav.classList.remove('is-active')
      })
    }
  }
}

Header.init()
