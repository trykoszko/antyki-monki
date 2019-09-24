<?php
    $queried_cat = get_query_var('category_name');
    $queried_page = get_query_var('paged') === 0 ? 1 : get_query_var('paged');
    $queried_per_page = get_query_var('posts_per_page');
    $queried_category = get_category(get_cat_ID($queried_cat));

    if ( isset($queried_category->count) ) {
        $queried_cat_count = $queried_category->count;
        $total_posts = $queried_cat ? $queried_cat_count : wp_count_posts('product')->publish;
    } else {
        $queried_cat_count = 0;
        $total_posts = $queried_cat ? $queried_cat_count : wp_count_posts('product')->publish;
    }

    $total_pages = ceil($total_posts / $queried_per_page);

    $url_string = '';
    if ($queried_cat) {
        $url_string .= '/category/' . $queried_cat;
    }
    if ($queried_page) {
        $is_first_page = $queried_page === 1;
        $is_last_page = $total_pages <= $queried_page;

        if (!$is_first_page) {
            $page = $queried_page - 1;
            $prev_url_string = $url_string . '/page/' . $page;
        }

        $current_url_string = $url_string . '/page/' . $queried_page;

        if (!$is_last_page) {
            $page = $queried_page + 1;
            $next_url_string = $url_string . '/page/' . $page;
        }
    }
?>

<?php if (isset($prev_url_string) || isset($next_url_string)) : ?>
    <ul class="c-pagination">

        <?php if (isset($prev_url_string)) : ?>
            <li class="c-pagination__item">
                <a class="c-link" href="<?php echo $prev_url_string; ?>" aria-label="Previous">
                    <span class="c-label">&laquo;</span>
                </a>
            </li>

            <li class="c-pagination__item">
                <a class="c-link" href="<?php echo $prev_url_string; ?>">
                    <span class="c-label"><?php echo $queried_page - 1; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <li class="c-pagination__item is-active">
            <a class="c-link">
                <span class="c-label"><?php echo $queried_page; ?></span>
            </a>
        </li>

        <?php if (isset($next_url_string)) : ?>
            <li class="c-pagination__item">
                <a class="c-link" href="<?php echo $next_url_string; ?>">
                    <span class="c-label"><?php echo $queried_page + 1; ?></span>
                </a>
            </li>

            <li class="c-pagination__item">
                <a class="c-link" href="<?php echo $next_url_string; ?>" aria-label="Next">
                    <span class="c-label">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>

    </ul>
<?php endif; ?>
