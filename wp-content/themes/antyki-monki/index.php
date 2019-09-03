<?php
get_header();
$cat = get_queried_object();
if ($cat && $cat->taxonomy === 'category') {
    $cat_name = $cat->name;
}
?>
<?php if ( have_posts() ) : ?>

    <div class="col-9 c-item-grid">
        <div class="container-fluid">
            <?php if (is_archive()) : ?>
                <div class="row">
                    <div class="col-12">
                        <?php get_template_part('components/jumbotron'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-12">
                    <div class="js-macy">
                        <?php while ( have_posts() ) : the_post(); ?>
                                <div class="card">
                                    <div class="card-inside">
                                        <?php
                                            $gallery = get_field('product_gallery');
                                        ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if ($gallery) : ?>
                                                <img class="card-img-top" src="<?php echo $gallery[0]['sizes']['large']; ?>" alt="<?php the_title(); ?>" />
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h4 class="card-title font-weight-bold">
                                                    <?php the_title(); ?>
                                                </h4>
                                                <?php
                                                    $cats = get_the_category();
                                                    $cat_count = count($cats);
                                                    $i = 1;
                                                ?>
                                                <?php foreach ($cats as $cat) : ?>
                                                    <?php echo $cat->name; ?><?php if ($i < $cat_count) { echo ', '; } ?>
                                                    <?php $i++; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <?php if (get_previous_posts_link() || get_next_posts_link()) : ?>
                <div class="row my-5">
                    <div class="col">
                        <?php get_template_part('components/pagination'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php else : ?>
    <div class="col-9">
        <h4>
            Nic nie znaleziono.
        </h4>
    </div>
<?php endif; ?>
<?php
get_footer();
