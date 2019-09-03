<?php
    global $post;
    $queried_obj = get_queried_object();
    $is_category = $queried_obj->taxonomy === 'category';
    $is_post = is_single();
    $title = $is_category ? $queried_obj->name : $post->post_title;
    $desc = $is_category ? $queried_obj->description : '';
?>
<div class="c-archive-heading mb-3">
    <div class="container py-2">
        <h2 class="mb-3">
            <?php if ($is_category) : ?>
                <span class="font-weight-bold">Kategoria:</span>
            <?php endif; ?>
            <?php echo $title; ?>
        </h2>
        <?php if ($desc) : ?>
            <p class="lead"><?php echo $desc; ?></p>
        <?php endif; ?>
    </div>
</div>
