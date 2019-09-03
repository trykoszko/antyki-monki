<?php
    $queried_cat = get_query_var('category_name');
    $queried_page = get_query_var('paged') === 0 ? 1 : get_query_var('paged');
    $queried_per_page = get_query_var('posts_per_page');

    $total_posts = $queried_cat ? get_category(get_cat_ID($queried_cat))->count : wp_count_posts('product')->publish;
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
    <nav>
        <ul class="pagination justify-content-center">

            <?php if (isset($prev_url_string)) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $prev_url_string; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <li class="page-item">
                    <a class="page-link" href="<?php echo $prev_url_string; ?>"><?php echo $queried_page - 1; ?></a>
                </li>
            <?php endif; ?>

            <li class="page-item active">
                <a class="page-link" href="<?php echo $current_url_string; ?>"><?php echo $queried_page; ?></a>
            </li>

            <?php if (isset($next_url_string)) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $next_url_string; ?>"><?php echo $queried_page + 1; ?></a>
                </li>

                <li class="page-item">
                    <a class="page-link" href="<?php echo $next_url_string; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
<?php endif; ?>
