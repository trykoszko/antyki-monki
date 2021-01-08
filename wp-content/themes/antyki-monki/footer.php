<?php
    $theme_url = get_template_directory_uri() . '/front';
    $footer_cols = get_field('footer_descs', 'option');
?>

</main>

<?php if ( is_singular('product') ) : ?>
    <?php get_template_part('components/image-modal'); ?>
<?php endif; ?>

<?php get_template_part('components/search-modal'); ?>

<footer class="l-footer">
    <div class="l-inner">
        <div class="c-footer">
            <?php if ($footer_cols) : ?>
                <div class="c-footer__row">
                    <?php foreach ($footer_cols as $col) : ?>
                        <div class="c-footer__col">
                            <h3 class="c-footer__title">
                                <?php echo $col['title']; ?>
                            </h3>
                            <div class="c-footer__text">
                                <?php echo $col['text']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php
                wp_nav_menu( array(
                    'theme_location'  => 'footer-menu',
                    'depth'	          => 1,
                    'container'       => false,
                    'menu_class'      => 'c-footer__nav',
                    'walker'          => new Footer_Walker
                ) );
            ?>
            <p class="c-footer__info">
                <?php _e('Przedstawiona oferta ma charakter informacyjny i nie stanowi oferty handlowej w rozumieniu Art. 66 par. 1 Kodeksu Cywilnego.', 'antyki'); ?>
            </p>
            <p class="c-footer__copyright">
                &copy; <?php echo get_bloginfo('name') . ' ' . date('Y'); ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
