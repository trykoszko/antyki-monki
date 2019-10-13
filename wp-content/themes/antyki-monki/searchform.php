<?php
    $theme_url = get_template_directory_uri() . '/front';
?>

<form class="c-search-form" action="/" method="get">
    <div class="c-search-form__group">
        <h2 class="t-h1"><?php _e('Szukaj...', 'antyki'); ?></h2>
    </div>
    <div class="c-search-form__group">
        <input
            class="c-search-form__input"
            type="text"
            name="s"
            id="search"
            placeholder="<?php _e('Wpisz frazę...', 'antyki'); ?>"
            value="<?php the_search_query(); ?>"
            />
        <button
            class="c-search-form__submit c-btn"
            type="submit"
            >
            <?php _e('Szukaj', 'antyki'); ?>
        </button>
    </div>
</form>
