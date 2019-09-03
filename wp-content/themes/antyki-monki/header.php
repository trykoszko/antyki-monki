<?php
    $theme_url = get_template_directory_uri() . '/front';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-light' ); ?>>

<header class="l-header js-header">
    <div class="l-inner">
        <div class="c-header">
            <nav class="c-header__nav js-header-nav">
                <button class="c-header__nav-close js-close-nav">
                    <svg class="ui-icon c-icon c-icon--search">
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
            <a href="/">
                <figure class="c-header__logo">
                    <img src="<?php echo get_field('logo', 'option'); ?>" alt="<?php echo get_bloginfo('title'); ?> Logo" />
                </figure>
            </a>
            <button class="c-header__search js-toggle-search">
                <svg class="ui-icon c-icon c-icon--search">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?php echo $theme_url; ?>/static/symbol/svg/sprite.symbol.svg#search"></use>
                </svg>
            </button>
            <button class="c-header__toggle js-toggle-menu">
                <span class="c-label"></span>
            </button>
        </div>
    </div>
</header>

<main class="l-main">
