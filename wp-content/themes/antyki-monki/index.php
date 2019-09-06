<?php
get_header();
$cat = get_queried_object();
$current_cat_name = '';
if ($cat && $cat->taxonomy === 'category') {
    $current_cat_name = $cat->name;
}
$all_cats = get_categories( array(
    'hide_empty' => false,
    'orderby' => 'count',
    'order' => 'DESC'
) );
?>

    <div class="l-inner l-inner--wide">
        <?php if ($all_cats) : ?>
            <div class="c-accordion js-accordion">
                <h2 class="c-section-title c-accordion__title js-accordion-title">
                    <?php _e('Kategorie', 'antyki'); ?>
                </h2>
                <ul class="c-main-cats c-accordion__content js-accordion-content">
                    <?php foreach ($all_cats as $cat) : ?>
                        <?php
                            $is_disabled = $cat->count === 0;
                            $is_current = $cat->name === $current_cat_name;
                        ?>
                        <li class="c-main-cats__cat<?php if ($is_current) : ?> c-main-cats__cat--current<?php endif; ?>">
                            <a class="c-link" <?php if (!$is_disabled) : ?>href="<?php echo get_category_link($cat); ?>"<?php endif; ?>>
                                <span class="c-label"><?php echo $cat->name; ?> (<?php echo $cat->count; ?>)<span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if ( have_posts() ) : ?>
            <h2 class="c-section-title">
                <?php if ($current_cat_name) : ?>
                <?php _e('Kategoria: ', 'antyki'); ?> <?php echo $current_cat_name; ?>
                <?php else : ?>
                    <?php _e('Wszystkie produkty', 'antyki'); ?>
                <?php endif; ?>
            </h2>
            <div class="c-main-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="c-card">
                        <div class="c-card__inside">
                            <?php
                                $gallery = get_field('product_gallery');
                                $first_img = $gallery[0]['sizes']['large'];
                                $first_img_aspect_ratio = round($gallery[0]['sizes']['large-width'] / $gallery[0]['sizes']['large-height'], 2);
                                $first_img_object_fit = $first_img_aspect_ratio === 1.78 || $first_img_aspect_ratio === 0.56 ? 'contain' : 'cover';
                                $second_img = $gallery[1]['sizes']['large'];
                                $second_img_aspect_ratio = round($gallery[1]['sizes']['large-width'] / $gallery[1]['sizes']['large-height'], 2);
                                $second_img_object_fit = $second_img_aspect_ratio === 1.78 || $second_img_aspect_ratio === 0.56 ? 'contain' : 'cover';
                            ?>
                            <?php if ($gallery) : ?>
                                <div class="c-card-gallery">
                                    <figure class="c-card-gallery__img c-card-gallery__img--first">
                                        <img
                                            class="c-img o-object-fit-<?php echo $first_img_object_fit; ?>"
                                            src="<?php echo $gallery[0]['sizes']['large']; ?>" alt="<?php the_title(); ?>"
                                            />
                                    </figure>
                                    <figure class="c-card-gallery__img c-card-gallery__img--second">
                                        <img
                                            class="c-img o-object-fit-<?php echo $second_img_object_fit; ?>"
                                            src="<?php echo $gallery[1]['sizes']['large']; ?>" alt="<?php the_title(); ?>"
                                            />
                                    </figure>
                                </div>
                            <?php endif; ?>
                            <div class="c-card__body">
                                <h4 class="c-card__title">
                                    <?php the_title(); ?>
                                </h4>
                                <?php
                                    $cats = get_the_category();
                                    $cat_count = count($cats);
                                    $i = 1;
                                ?>
                                <p class="c-card__categories">
                                    <?php foreach ($cats as $cat) : ?>
                                        <a class="c-link" href="<?php echo get_category_link($cat); ?>"><span class="c-label"><?php echo $cat->name; ?></span></a><?php if ($i < $cat_count) { echo ', '; } ?>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div class="c-main-grid c-main-grid--empty">
                <h4 class="h4">
                    <?php _e('Nie znaleziono produktów', 'antyki'); ?>
                </h4>
            </div>
        <?php endif; ?>
    </div>

<?php
get_footer();
