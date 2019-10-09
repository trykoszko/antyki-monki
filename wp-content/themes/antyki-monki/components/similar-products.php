<?php
    $categories = get_the_category($post->ID);
    $cat_ids = array();
    foreach ($categories as $cat) {
        $cat_ids[] = $cat->term_id;
    }
    $args = array(
        'post_type' => 'product',
        'orderby' => 'rand',
        'category__in' => $cat_ids,
        'posts_per_page' => 9,
        'fields' => 'ids'
    );
    $posts_with_duplicates = get_posts( $args );
    $posts_without_current = array_splice( $posts_with_duplicates, array_search( $post->ID, $posts_with_duplicates ), 1 );
    $posts = array_slice( array_unique( $posts_with_duplicates ), 0, 3 );
?>

<?php if ($posts) : ?>
    <section class="l-related-posts">
        <div class="l-inner">
            <h2 class="c-section-title">
                <?php _e('Podobne produkty', 'antyki'); ?>
            </h2>
            <ul class="c-related-posts">
                <?php foreach ($posts as $post_id) : ?>
                    <?php
                        $thumb = get_field('product_gallery', $post_id)[0];
                        $thumb_aspect_ratio = round($thumb['width'] / $thumb['height'], 2);
                        $thumb_orientation = $thumb_aspect_ratio > 1 ? 'landscape' : 'portrait';
                    ?>
                    <li class="c-related-post">
                        <a href="<?php echo get_permalink($post_id); ?>">
                            <figure class="c-related-post__img c-related-post__img--<?php echo $thumb_orientation; ?>">
                                <img src="<?php echo $thumb['sizes']['medium']; ?>" />
                            </figure>
                            <h4 class="c-related-post__title">
                                <?php echo get_the_title($post_id); ?>
                            </h4>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
<?php endif; ?>
