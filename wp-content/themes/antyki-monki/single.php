<?php
get_header();
$slides = get_field('product_gallery');
$is_painting = isset(get_field('product_attributes')['canvas_type']);
?>
<?php if ( have_posts() ) : ?>

    <div class="col-9 c-single-item">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php get_template_part('components/jumbotron'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="homepageCarousel" class="carousel slide w-100 c-single-carousel" data-ride="carousel">
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
                                <div class="carousel-item bg-white<?php if ($i === 1) : ?> active<?php endif; ?>">
                                    <button data-toggle="modal" data-target="#singleModal">
                                        <img src="<?php echo $slide['sizes']['medium_large']; ?>" alt="<?php echo $slide['name']; ?>">
                                    </button>
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
                    <div class="modal c-product-modal-carousel" tabindex="-1" role="dialog" id="singleModal" aria-hidden="true">
                        <div class="modal-dialog mt-5" role="document">
                            <div class="modal-content">
                                <div class="modal-header rounded-top">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="productCarousel" class="carousel slide w-100 c-single-carousel" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            <?php $i = 1; ?>
                                            <?php foreach ($slides as $slide) : ?>
                                                <li data-target="#productCarousel" data-slide-to="<?php echo $i; ?>" <?php if ($i === 0) : ?>class="active"<?php endif; ?>></li>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </ol>
                                        <div class="carousel-inner">
                                            <?php $i = 1; ?>
                                            <?php foreach ($slides as $slide) : ?>
                                                <div class="carousel-item<?php if ($i === 1) : ?> active<?php endif; ?>">
                                                    <img src="<?php echo $slide['sizes']['medium_large']; ?>" alt="<?php echo $slide['name']; ?>">
                                                </div>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Poprzednie</span>
                                        </a>
                                        <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Następne</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="container-fluid my-4 c-product-attributes">
                        <?php
                            $attrs = get_field('product_attributes');
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <p class="h4 font-weight-bold">
                                    Opis
                                </p>
                                <p class="h5"><?php echo $attrs['desc']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="h5">Szerokość (cm)</p>
                            </div>
                            <div class="col-6">
                                <p class="h5"><?php echo $attrs['width']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="h5">Wysokość (cm)</p>
                            </div>
                            <div class="col-6">
                                <p class="h5"><?php echo $attrs['height']; ?></p>
                            </div>
                        </div>
                        <?php if ($is_painting) : ?>
                            <div class="row">
                                <div class="col-6">
                                    <p class="h5">Rodzaj podobrazia</p>
                                </div>
                                <div class="col-6">
                                    <p class="h5"><?php echo $attrs['canvas_type']['label']; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="h5">Rodzaj wykończenia</p>
                                </div>
                                <div class="col-6">
                                    <p class="h5"><?php echo $attrs['paint_type']['label']; ?></p>
                                </div>
                            </div>
                            <?php if ($attrs['additional']) : ?>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="h5">Dodatkowe informacje</p>
                                    </div>
                                    <div class="col-6">
                                        <ul>
                                            <?php foreach ($attrs['additional'] as $attr) : ?>
                                                <li class="h5"><?php echo $attr['label']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-6">
                                    <p class="h5">Głębokość (cm)</p>
                                </div>
                                <div class="col-6">
                                    <p class="h5">
                                        <?php echo $attrs['depth']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="h5">Materiał wykonania</p>
                                </div>
                                <div class="col-6">
                                    <?php
                                        $materials = count($attrs['material']);
                                        $i = 1;
                                    ?>
                                    <?php foreach ($attrs['material'] as $material) : ?>
                                        <p class="h5">
                                            <?php echo $material; ?><?php if ($i < $materials) : ?>,<?php endif; ?>
                                        </p>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php if ($attrs['additional']) : ?>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="h5">Dodatkowe informacje</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="h5"><?php echo $attrs['additional']; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- todo: zrobic related products here by 3 -->
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
