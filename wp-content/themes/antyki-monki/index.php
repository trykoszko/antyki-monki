<?php
get_header();

$cat = get_queried_object();
$current_cat_name = '';
if ($cat && $cat->taxonomy === 'category') {
    $current_cat_name = $cat->name;
}
$all_cats = get_categories( array(
    'orderby' => 'count',
    'order' => 'DESC'
) );
$cats_count = count($all_cats);

$query_args = array(
    'post_type' => 'product'
);

$is_search = get_search_query();
if ($is_search) {
    $query_args['s'] = $is_search;
    $query_args['posts_per_page'] = 99;
}

$queried_page = get_query_var('paged') === 0 ? 1 : get_query_var('paged');
if ($queried_page) {
    $query_args['paged'] = $queried_page;
}

$query = new WP_Query($query_args);

$found_posts = $query->found_posts;
?>

    <div class="l-inner l-inner--wide">
        <?php if ($all_cats && !$is_search) : ?>
            <div class="c-accordion js-accordion">
                <h2 class="c-section-title c-accordion__title js-accordion-title">
                    <?php if ($current_cat_name) : ?>
                        <?php _e('Kategoria', 'antyki'); ?>: <?php echo $current_cat_name; ?> (<?php echo $cat->count; ?>)
                    <?php else : ?>
                        <?php _e('Kategorie', 'antyki'); ?>
                    <?php endif; ?>
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
        <?php if ( $query->have_posts() ) : ?>
            <?php if (!$current_cat_name && !$is_search) : ?>
                <h2 class="c-section-title">
                    <?php _e('Wszystkie produkty', 'antyki'); ?> (<?php echo $found_posts; ?>)
                </h2>
            <?php endif; ?>
            <?php if ($current_cat_name) : ?>
                <a href="/" class="c-section-link">
                    <span class="c-label">
                        <?php _e('&laquo; Powrót do wszystkich produktów', 'antyki'); ?>
                    </span>
                </a>
            <?php endif; ?>
            <?php if ($is_search) : ?>
                <h2 class="c-section-title">
                    <?php _e('Wyniki wyszukiwania frazy: ', 'antyki'); ?> <i><?php echo get_search_query(); ?></i> (<?php echo $found_posts; ?>)
                </h2>
            <?php endif; ?>
            <div class="c-main-grid">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="c-card">
                        <div class="c-card__inside">
                            <?php
                                $gallery = get_field('product_gallery');
                                $first_img = $gallery[0]['sizes']['large'];
                                $first_img_aspect_ratio = round($gallery[0]['sizes']['large-width'] / $gallery[0]['sizes']['large-height'], 2);
                                $first_img_object_fit = $first_img_aspect_ratio === 1.78 || $first_img_aspect_ratio === 0.56 ? 'contain' : 'cover';
                                $first_img_orientation = $first_img_aspect_ratio > 1 ? 'landscape' : 'portrait';
                                $second_img = $gallery[1]['sizes']['large'];
                                $second_img_aspect_ratio = round($gallery[1]['sizes']['large-width'] / $gallery[1]['sizes']['large-height'], 2);
                                $second_img_object_fit = $second_img_aspect_ratio === 1.78 || $second_img_aspect_ratio === 0.56 ? 'contain' : 'cover';
                                $second_img_orientation = $second_img_aspect_ratio > 1 ? 'landscape' : 'portrait'
                            ?>
                            <?php if ($gallery) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <div class="c-card-gallery">
                                        <figure class="c-card-gallery__img c-card-gallery__img--<?php echo $first_img_orientation; ?> c-card-gallery__img--first">
                                            <img
                                                class="c-img o-object-fit-<?php echo $first_img_object_fit; ?>"
                                                src="<?php echo $gallery[0]['sizes']['large']; ?>" alt="<?php the_title(); ?>"
                                                />
                                        </figure>
                                        <figure class="c-card-gallery__img c-card-gallery__img--<?php echo $second_img_orientation; ?> c-card-gallery__img--second">
                                            <img
                                                class="c-img o-object-fit-<?php echo $second_img_object_fit; ?>"
                                                src="<?php echo $gallery[1]['sizes']['large']; ?>" alt="<?php the_title(); ?>"
                                                />
                                        </figure>
                                    </div>
                                </a>
                            <?php endif; ?>
                            <div class="c-card__body">
                                <a href="<?php the_permalink(); ?>">
                                    <h4 class="c-card__title">
                                        <?php the_title(); ?>
                                    </h4>
                                </a>
                                <?php
                                    $cats = get_the_category();
                                    $cat_count = count($cats);
                                    $i = 1;
                                ?>
                                <p class="c-card__categories">
                                    <?php _e('Kategoria:', 'antyki'); ?>
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
            <?php if ($current_cat_name) : ?>
                <a href="/" class="c-section-link">
                    <span class="c-label">
                        <?php _e('&laquo; Powrót do wszystkich produktów', 'antyki'); ?>
                    </span>
                </a>
            <?php endif; ?>
            <?php if (!$is_search) : ?>
                <?php get_template_part('components/pagination'); ?>
            <?php endif; ?>
        <?php else : ?>
            <div class="c-main-grid c-main-grid--empty">
                <h2 class="c-section-title">
                    <?php _e('Nie znaleziono produktów.', 'antyki'); ?>
                </h2>
                <a href="#" class="c-section-link" onclick="window.history.go('-1')">
                    <span class="c-label">
                        <?php _e('&laquo; Powrót do wszystkich produktów', 'antyki'); ?>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>

<?php
get_footer();
