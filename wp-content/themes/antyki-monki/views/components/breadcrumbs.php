<?php
    $categories = get_the_category();
    $categories_count = count($categories);
?>

<ul class="c-breadcrumbs">
    <li class="c-breadcrumbs__item">
        <a class="c-link" href="/">
            <span class="c-label">
                <?php bloginfo('name'); ?>
            </span>
        </a>
    </li>
    <li class="c-breadcrumbs__item">
        <?php $i = 1; ?>
        <?php foreach ($categories as $cat) : ?>
            <a href="<?php echo get_category_link($cat); ?>" class="c-link">
                <span class="c-label">
                    <?php echo $cat->name; ?><?php echo $i < $categories_count ? ', ' : ''; ?>
                </span>
            </a>
            <?php $i++; ?>
        <?php endforeach; ?>
    </li>
    <li class="c-breadcrumbs__item">
        <a class="c-link">
            <span class="c-label">
                <?php the_title(); ?>
            </span>
        </a>
    </li>
</ul>
