import $ from 'jquery'

const figureTemplate = () => {
  return `<figure class="c-card-gallery__img c-card-gallery__img--' . $img['orientation'] . ' c-card-gallery__img--' . $img['name'] . '">
    <img class="c-img o-object-fit-' . $img['object_fit'] . '" src="' . $img['src'] . '" alt="' . $img['alt'] . '" />
  </figure>`
}
