import noUiSlider from 'nouislider'
import $ from 'jquery'

const $priceSlider = $('.js-productlist-price')
const $priceInputs = $('.js-productlist-price-input')

const initSliders = () => {
  const sliders = document.querySelectorAll('.js-range-slider')
  if (sliders) {
    Array.from(sliders).forEach(slider => {
      const {
        start,
        connect,
        rangeMin,
        rangeMax
      } = slider.dataset
      noUiSlider.create(slider, {
        start: start ? JSON.parse(start) : [20, 80],
        connect: connect ? JSON.parse(connect) : [false, true, false],
        range: {
          'min': rangeMin ? JSON.parse(rangeMin) : [10],
          'max': rangeMax ? JSON.parse(rangeMax) : [100]
        },
        step: 10,
        tooltips: [true, true],
        format: {
          to: function (val) {
            return parseInt(val)
          },
          from: function (val) {
            return parseInt(val)
          }
        }
      })
    })
  }
}

const handlePrice = values => {
  const from = values[0]
  const to = values[1]
  $('.js-productlist-price-input[data-price="from"]').val(from)
  $('.js-productlist-price-input[data-price="to"]').val(to)
}

const handlePriceInput = e => {
  const $input = $(e.currentTarget)
  const type = $input.attr('data-price')
  const val = parseInt($input.val())
  $priceSlider[0].noUiSlider.set(type === 'from' ? [val, null] : [null, val])
}

initSliders()

if ($priceSlider.length) {
  $priceSlider[0].noUiSlider.on('change', handlePrice)
}

if ($priceInputs.length) {
  $priceInputs.each(function () {
    $(this).on('change', handlePriceInput)
  })
}
