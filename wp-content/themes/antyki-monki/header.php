<?php
    $theme_url = get_template_directory_uri() . '/front';
    $seo_title = is_home() ? get_bloginfo('title') : get_the_title() . ' - ' . get_bloginfo('title');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <title><?php echo $seo_title; ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="og:title" property="og:title" content="<?php echo $seo_title; ?>">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-P7GGVLS');</script>
    <!-- End Google Tag Manager -->

    <?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-light' ); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P7GGVLS"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <header class="l-header js-header">
        <div class="l-inner l-inner--wide">
            <div class="c-header">
                <a class="c-header__logo-wrapper" href="/">
                    <figure class="c-header__logo">
                        <img src="<?php echo $theme_url; ?>/static/img/antyki-logo.png" alt="Logo <?php echo get_bloginfo('title'); ?>" />
                    </figure>
                </a>
                <nav class="c-header__nav js-header-nav">
                    <button class="c-header__nav-close js-close-nav">
                        <svg class="ui-icon c-icon c-icon--close">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $theme_url; ?>/static/symbol/svg/sprite.symbol.svg#close"></use>
                        </svg>
                    </button>
                    <?php
                        wp_nav_menu( array(
                            'theme_location'  => 'header-menu',
                            'depth'	          => 1,
                            'container'       => false,
                            'menu_class'      => 'c-header__menu',
                            'walker'          => new Antyki_Walker
                        ) );
                    ?>
                </nav>
                <button class="c-header__search js-toggle-search">
                    <svg class="ui-icon c-icon c-icon--search">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $theme_url; ?>/static/symbol/svg/sprite.symbol.svg#search"></use>
                    </svg>
                </button>
                <button class="c-header__toggle js-toggle-menu">
                    <svg class="ui-icon c-icon c-icon--burger">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $theme_url; ?>/static/symbol/svg/sprite.symbol.svg#burger"></use>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main class="l-main">

        <?php if (is_home()) : ?>
            <?php get_template_part('components/home-hero'); ?>
        <?php endif; ?>
