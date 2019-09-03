<?php
?>

<nav class="navbar navbar-expand-lg navbar-light c-header mb-5">
    <div class="container">
        <?php if (is_single() || is_archive()) : ?>
            <a class="navbar-brand text-bold" href="/"><?php echo get_bloginfo('title'); ?></a>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
            wp_nav_menu( array(
                'theme_location'  => 'header-menu',
                'depth'	          => 2,
                'container'       => 'div',
                'container_class' => 'collapse navbar-collapse',
                'container_id'    => 'navbarResponsive',
                'menu_class'      => 'navbar-nav ml-auto',
                'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                'walker'          => new WP_Bootstrap_Navwalker(),
            ) );
        ?>
    </div>
</nav>
