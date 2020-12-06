<?php
$bg_image = get_field('homepage_hero_img', 'option');
?>
<section class="l-hero" <?php $bg_image ? "style='background-image: url('$bg_image');" : '' ?>>
    <div class="c-hero-slider">
        <div class="l-inner">
            <div class="c-hero-slider__content">
                <h1><?php _e( 'Antyki Mońki', 'antyki' ); ?></h1>
                <p><?php _e( 'Lorem ipsum', 'antyki' ); ?></p>
            </div>
        </div>
    </div>
</section>
