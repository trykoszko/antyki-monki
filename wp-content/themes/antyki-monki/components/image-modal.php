<?php
    $theme_url = get_template_directory_uri() . '/front';
?>

<section class="l-image-modal js-image-modal">
    <div class="l-inner">
        <div class="c-image-modal">
            <button class="c-image-modal__close js-close-image-modal">
                <svg class="ui-icon c-icon c-icon--close">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $theme_url; ?>/static/symbol/svg/sprite.symbol.svg#close"></use>
                </svg>
            </button>
            <figure class="c-image-modal__img js-image">
                <img />
            </figure>
        </div>
    </div>
</section>
