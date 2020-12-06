<?php
get_header();

$cat = get_queried_object();
$current_cat_name = '';
$current_cat_id = '';
if ($cat && property_exists($cat, 'taxonomy') && $cat->taxonomy === 'category') {
    $current_cat_name = $cat->name;
    $current_cat_id = $cat->term_id;
}
$all_cats = get_categories(array(
    'orderby' => 'count',
    'order' => 'DESC'
));
$cats_count = count($all_cats);

$query_args = array(
    'post_type' => 'product'
);

if (isset($current_cat_id) && $current_cat_id !== null) {
    $query_args['cat'] = $current_cat_id;
}

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

<div class="l-inner">
    <?php if ($query->have_posts()) : ?>
        <?php // if (!$current_cat_name && !$is_search) : ?>
            <!-- <h2 class="c-section-title">
                <?php // _e('Wszystkie produkty', 'antyki'); ?> (<?php // echo $found_posts; ?>)
            </h2> -->
        <?php // endif; ?>
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
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="c-card">
                    <div class="c-card__inside">
                        <?php
                        $gallery = get_field('product_gallery');
                        $first_img = $gallery[0]['sizes']['large'];
                        $second_img = $gallery[1]['sizes']['large'];
                        $first_img_exists = false;
                        $second_img_exists = false;
                        if ($first_img) {
                            $first_img_exists = true;
                            $first_img_aspect_ratio = round($gallery[0]['sizes']['large-width'] / $gallery[0]['sizes']['large-height'], 2);
                            $first_img_object_fit = $first_img_aspect_ratio === 1.78 || $first_img_aspect_ratio === 0.56 ? 'contain' : 'cover';
                            $first_img_orientation = $first_img_aspect_ratio > 1 ? 'landscape' : 'portrait';
                        }
                        if ($second_img) {
                            $second_img_exists = true;
                            $second_img_aspect_ratio = round($gallery[1]['sizes']['large-width'] / $gallery[1]['sizes']['large-height'], 2);
                            $second_img_object_fit = $second_img_aspect_ratio === 1.78 || $second_img_aspect_ratio === 0.56 ? 'contain' : 'cover';
                            $second_img_orientation = $second_img_aspect_ratio > 1 ? 'landscape' : 'portrait';
                        }
                        ?>
                        <?php if ($gallery) : ?>
                            <div class="c-card-gallery">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if ($first_img_exists) : ?>
                                        <figure class="c-card-gallery__img c-card-gallery__img--<?php echo $first_img_orientation; ?> c-card-gallery__img--first">
                                            <img class="c-img o-object-fit-<?php echo $first_img_object_fit; ?>" src="<?php echo $gallery[0]['sizes']['large']; ?>" alt="<?php the_title(); ?>" />
                                        </figure>
                                    <?php endif; ?>
                                    <?php if ($second_img_exists) : ?>
                                        <figure class="c-card-gallery__img c-card-gallery__img--<?php echo $second_img_orientation; ?> c-card-gallery__img--second">
                                            <img class="c-img o-object-fit-<?php echo $second_img_object_fit; ?>" src="<?php echo $gallery[1]['sizes']['large']; ?>" alt="<?php the_title(); ?>" />
                                        </figure>
                                    <?php endif; ?>
                                </a>
                            </div>
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
                                <?php foreach ($cats as $cat) : ?>
                                    <a class="c-link" href="<?php echo get_category_link($cat); ?>"><span class="c-label"><?php echo $cat->name; ?></span></a><?php if ($i < $cat_count) { echo '<span class="c-decor"></span>'; } ?>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            </p>
                            <?php
                            $attrs = get_field( 'olx_attributes', $post );
                            $price = $attrs['price'] ?? null;
                            ?>
                            <?php if ($price) : ?>
                                <?php
                                $price = intval($price);
                                $price = $price % 10 === 0 ? $price - 1 : $price;
                                ?>
                                <p class="c-card__price">
                                    <?php echo $price; ?>,-
                                </p>
                            <?php endif; ?>
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
            <?php // get_template_part('components/pagination'); ?>
            <a href="#" class="c-btn c-btn--golden">
                <?php _e( 'Zobacz wszystkie', 'antyki' ); ?>
            </a>
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
