const Accordion = {
  init: function () {
    const accordions = document.querySelectorAll('.js-accordion')
    Array.from(accordions).forEach(accordion => {
      const title = accordion.querySelector('.js-accordion-title')
      title.addEventListener('click', e => {
        accordion.classList.toggle('is-active')
      })
    })
  }
}

Accordion.init()
