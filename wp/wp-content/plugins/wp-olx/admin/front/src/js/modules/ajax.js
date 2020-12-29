import $ from 'jquery'

const { ajaxUrl, ajaxActions, prompt_areYouSure } = wpTranslates

const buttons = document.querySelectorAll('.js-wp-olx-ajax-product')
if (buttons) {
  Array.from(buttons).forEach(button => {
    button.addEventListener('click', e => {
      e.preventDefault()
      if (window.confirm(prompt_areYouSure)) {
        const btn = e.currentTarget
        const productId = btn.dataset.productId
        const actionType = btn.dataset.actionType
        $.ajax({
          method: 'GET',
          url: ajaxUrl,
          dataType: 'json',
          contentType: 'application/json',
          data: {
            action: actionType,
            product_id: productId
          }
        })
          .done(res => {
            console.log('res', res)
            window.location.reload()
          })
          .fail(err => {
            console.log('err', err)
          })
      }
    })
  })
}
