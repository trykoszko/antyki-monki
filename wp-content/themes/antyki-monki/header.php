<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-light' ); ?>>

<?php get_template_part('components/navbar'); ?>

<div class="container mt-1">

    <div class="row">

        <div class="col-3 c-sidebar">
            <?php get_template_part('components/sidebar'); ?>
        </div>
