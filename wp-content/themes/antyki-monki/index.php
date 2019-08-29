<?php
/**
 * Template name: Homepage
 */
get_header();
$args = Array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged ? $paged : 1
);
query_posts($args);
$slide_count = 5;
$slides = get_field('slider_items');
?>

<?php if ( have_posts() ) : ?>

    <div class="container-fluid c-hero bg-light">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div id="homepageCarousel" class="carousel slide w-100" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php $i = 1; ?>
                            <?php foreach ($slides as $slide) : ?>
                                <li data-target="#homepageCarousel" data-slide-to="<?php echo $i; ?>" <?php if ($i === 0) : ?>class="active"<?php endif; ?>></li>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php $i = 1; ?>
                            <?php foreach ($slides as $slide) : ?>
                                <?php
                                    $first_image = get_field('product_gallery', $slide['post']->ID)[0]['url'];
                                    $title = $slide['post']->post_title;
                                ?>
                                <div class="carousel-item <?php if ($i === 1) : ?>active<?php endif; ?>" style="background-image: url(<?php echo $first_image; ?>);">
                                    <!-- <img src="<?php //echo $first_image; ?>" alt="<?php //echo $title; ?>"> -->
                                    <div class="carousel-caption d-none d-md-block">
                                        <a href="<?php the_permalink(); ?>">
                                            <h3><?php echo $title; ?></h3>
                                        </a>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#homepageCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Poprzednie</span>
                        </a>
                        <a class="carousel-control-next" href="#homepageCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Następne</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">

        <div class="row">

            <div class="col-12 c-item-grid">
                <div class="js-macy">
                    <?php while ( have_posts() ) : the_post(); ?>
                            <div class="card">
                                <div class="card-inside">
                                    <?php
                                        $gallery = get_field('product_gallery');
                                    ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if ($gallery) : ?>
                                            <img class="card-img-top" src="<?php echo $gallery[0]['url']; ?>" alt="<?php the_title(); ?>" />
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

            lazyload here

        </div>
    </div>
<?php else : ?>
    <div class="col-12">
        <h4 class="card-title">
            Nic nie znaleziono.
        </h4>
    </div>
<?php endif; ?>
<?php
get_footer();
