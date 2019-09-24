<?php
    $theme_url = get_template_directory_uri() . '/front';
?>

</main>

<?php if ( is_singular('product') ) : ?>
    <?php get_template_part('components/image-modal'); ?>
<?php endif; ?>

<?php get_template_part('components/search-modal'); ?>

<footer class="l-footer">
    <div class="l-inner">
        <div class="c-footer">
            <?php
                wp_nav_menu( array(
                    'theme_location'  => 'footer-menu',
                    'depth'	          => 1,
                    'container'       => false,
                    'menu_class'      => 'c-footer__nav',
                    'walker'          => new Antyki_Footer_Walker
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
