<?php
get_header();
$args = Array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged ? $paged : 1
);
query_posts($args);
?>

<div class="container">

    <div class="row">

        <div class="col-lg-3">
            <h1 class="my-4"><?php echo get_bloginfo('description'); ?></h1>
            <div class="list-group">
                <?php
                    $categories = get_terms('category');
                ?>
                <?php foreach ($categories as $cat) : ?>
                    <a href="<?php echo get_category_link($cat); ?>" class="list-group-item"><?php echo $cat->name; ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-9">

            <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class=""></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <div class="carousel-item">
                    <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="First slide">
                </div>
                <div class="carousel-item active">
                    <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Second slide">
                </div>
                <div class="carousel-item">
                <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            </div>

            <div class="row">

                <?php if ( have_posts() ) : ?>
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <a href="<?php the_permalink(); ?>"><img class="card-img-top" src="<?php echo get_field('product_gallery')[0]['url']; ?>" alt="<?php the_title(); ?>"></a>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <!-- <h5>$24.99</h5>
                                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam aspernatur!</p> -->
                                </div>
                                <div class="card-footer">
                                    <?php
                                        $cats = get_the_category();
                                        $cat_count = count($cats);
                                        $i = 1;
                                    ?>
                                    <?php foreach ($cats as $cat) : ?>
                                        <a href="<?php echo get_category_link($cat); ?>">
                                            <?php echo $cat->name; ?><?php if ($i < $cat_count) { echo ', '; } ?>
                                        </a>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="col-12">
                        <h4 class="card-title">
                            Nie znaleziono.
                        </h4>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</div>

<?php
get_footer();
