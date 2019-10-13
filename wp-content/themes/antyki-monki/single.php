<?php
get_header();
$theme_url = get_template_directory_uri() . '/front';
$slides = get_field('product_gallery');
$is_painting = isset(get_field('product_attributes')['canvas_type']);
?>
<article class="l-article">
    <div class="l-inner l-inner--wide">
        <?php get_template_part('components/breadcrumbs'); ?>
        <a href="/" class="c-section-link">
            <span class="c-label">
                <?php _e('&laquo; Powrót do wszystkich produktów', 'antyki'); ?>
            </span>
        </a>
        <div class="c-product">
            <?php if ($slides) : ?>
                <div class="c-product-gallery swiper-container js-product-gallery-slider">
                    <div class="c-product-gallery__wrapper swiper-wrapper">
                        <?php foreach ($slides as $slide) : ?>
                            <?php
                                $img_aspect_ratio = round($slide['sizes']['large-width'] / $slide['sizes']['large-height'], 2);
                                $img_orientation = $img_aspect_ratio > 1 ? 'landscape' : 'portrait';
                            ?>
                            <div
                                class="c-product-gallery__slide swiper-slide js-slide"
                                data-full-size-url="<?php echo $slide['url']; ?>"
                                data-img-orientation="<?php echo $img_orientation; ?>"
                                >
                                <figure class="c-product-gallery__img">
                                    <img src="<?php echo $slide['sizes']['medium_large']; ?>" alt="<?php echo $slide['name']; ?>">
                                </figure>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button title="<?php _e('Poprzednie', 'antyki'); ?>" class="c-product-gallery__btn c-product-gallery__btn--prev">
                        <svg class="ui-icon c-icon c-icon--arrow">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $theme_url; ?>/static/symbol/svg/sprite.symbol.svg#arrow"></use>
                        </svg>
                    </button>
                    <button title="<?php _e('Następne', 'antyki'); ?>" class="c-product-gallery__btn c-product-gallery__btn--next">
                        <svg class="ui-icon c-icon c-icon--arrow">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $theme_url; ?>/static/symbol/svg/sprite.symbol.svg#arrow"></use>
                        </svg>
                    </button>
                    <ul class="c-product-gallery__nav">
                    </ul>
                </div>
            <?php endif; ?>
            <div class="c-product-info">
                <?php
                    $attrs = get_field('product_attributes');
                ?>
                <h1 class="c-product-info__title">
                    <?php the_title(); ?>
                </h1>
                <p class="c-product-info__desc">
                    <?php echo $attrs['desc']; ?>
                </p>
                <ul class="c-product-info__attrs">
                    <li class="c-product-info__attrs">
                        <div class="c-product-info__attr">
                            <span>Cechy produktu</span>
                        </div>
                    </li>
                    <li class="c-product-info__attrs">
                        <div class="c-product-info__attr">
                            <span>Szerokość</span>
                            <span><?php echo $attrs['width']; ?> cm</span>
                        </div>
                    </li>
                    <li class="c-product-info__attrs">
                        <div class="c-product-info__attr">
                            <span>Wysokość</span>
                            <span><?php echo $attrs['height']; ?> cm</span>
                        </div>
                    </li>
                    <?php if ($is_painting) : ?>
                        <li class="c-product-info__attrs">
                            <div class="c-product-info__attr">
                                <span>Rodzaj podobrazia</span>
                                <span><?php echo $attrs['canvas_type']['label']; ?></span>
                            </div>
                        </li>
                        <li class="c-product-info__attrs">
                            <div class="c-product-info__attr">
                                <span>Rodzaj wykończenia</span>
                                <span><?php echo $attrs['paint_type']['label']; ?></span>
                            </div>
                        </li>
                        <?php if ($attrs['additional']) : ?>
                            <li class="c-product-info__attrs">
                                <div class="c-product-info__attr">
                                    <span>Dodatkowe informacje</span>
                                    <ul class="c-product-info__sub-attrs">
                                        <?php foreach ($attrs['additional'] as $attr) : ?>
                                            <li class="c-product-info__sub-attr">
                                                <div class="c-product-info__attr">
                                                    <?php echo $attr['label']; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php else : ?>
                        <li class="c-product-info__attrs">
                            <div class="c-product-info__attr">
                                <span>Głębokość</span>
                                <span><?php echo $attrs['depth']; ?> cm</span>
                            </div>
                        </li>
                        <li class="c-product-info__attrs">
                            <div class="c-product-info__attr">
                                <?php
                                    $materials = count($attrs['material']);
                                    $i = 1;
                                ?>
                                <span>Materiał wykonania</span>
                                <span>
                                    <?php if ($materials > 1) : ?>
                                        <?php foreach ($attrs['material'] as $material) : ?>
                                            <?php echo $material; ?><?php if ($i < $materials) : ?>, <?php endif; ?>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <?php _e('Brak danych', 'antyki'); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </li>
                        <?php if ($attrs['additional']) : ?>
                            <li class="c-product-info__attrs">
                                <div class="c-product-info__attr">
                                    <span>Dodatkowe informacje</span>
                                    <ul class="c-product-info__sub-attrs">
                                        <?php foreach ($attrs['additional'] as $attr) : ?>
                                            <li class="c-product-info__sub-attr">
                                                <div class="c-product-info__attr">
                                                    <?php echo $attr['label']; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <!-- todo: zrobic related products here by 3 -->
    </div>
</article>

<?php get_template_part('components/similar-products'); ?>

<?php
get_footer();
