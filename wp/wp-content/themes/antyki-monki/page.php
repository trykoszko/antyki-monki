<?php
get_header();
?>

<div class="l-inner l-inner--narrow">

    <?php if (have_posts()) : ?>
        <?php while(have_posts()) : the_post(); ?>

            <div class="c-main-grid c-main-grid--empty">
                <h2 class="c-section-title">
                    <?php the_title(); ?>
                </h2>
                <div class="c-page-content">
                    <?php the_content(); ?>
                </div>
            </div>

            <a href="/" class="c-section-link">
                <span class="c-label">
                    <?php _e('&laquo; Powrót na stronę główną', 'antyki'); ?>
                </span>
            </a>

        <?php endwhile; ?>
    <?php else : ?>

        <h2 class="c-section-title">
            <?php _e('Nie znaleziono strony.', 'antyki'); ?>
        </h2>
        <a href="/" class="c-section-link">
            <span class="c-label">
                <?php _e('&laquo; Powrót do strony głównej', 'antyki'); ?>
            </span>
        </a>

    <?php endif; ?>

</div>

<?php
get_footer();
