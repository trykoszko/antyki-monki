<form class="c-search-form" action="/" method="get">
    <div class="c-search-form__group">
        <label class="c-search-form__label" for="search">
            <?php _e('Wyszukaj na stronie', 'antyki'); ?>
        </label>
        <input
            class="c-search-form__input"
            type="text"
            name="s"
            id="search"
            placeholder="<?php _e('Wpisz frazę...', 'antyki'); ?>"
            value="<?php the_search_query(); ?>"
            />
    </div>
    <div class="c-search-form__group">
        <button
            class="c-search-form__submit c-btn"
            type="submit"
            >
            <?php _e('Szukaj', 'antyki'); ?>
        </button>
    </div>
</form>
