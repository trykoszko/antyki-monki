<?php
    $cats = get_categories();
    $current_object = get_queried_object();
    $current_object_slug = '';
    if ($current_object && $current_object->taxonomy === 'category') {
        $current_object_slug = $current_object->slug;
    }
?>

<?php if (!is_single() && !is_archive()) : ?>
    <a href="/">
        <h1 class="mb-4"><?php echo bloginfo('name'); ?></h1>
    </a>
    <p><?php echo bloginfo('description'); ?></p>
    <hr>
<?php endif; ?>
<div class="list-group">
    <h5 class="list-group-item font-weight-bold">Kategorie</h5>
    <?php foreach ($cats as $cat) : ?>
        <?php
            $is_active = '';
            if ($current_object_slug === $cat->slug) {
                $is_active = true;
            }
        ?>
        <a href="<?php echo get_category_link($cat); ?>" class="list-group-item<?php if ($is_active) : ?> active<?php endif; ?>"><?php echo $cat->name; ?></a>
    <?php endforeach; ?>
        </div>
