<?php
function init_acf_options_page() {
    acf_add_options_page(
        array(
        'page_title'  => __( 'Opcje OLX', 'antyki' ),
        'menu_title'  => __( 'Opcje OLX', 'antyki' ),
        'menu_slug'   => 'theme-options',
        'capability'  => 'edit_posts',
        'redirect'    => false
        )
    );
    acf_add_options_page(
        array(
        'page_title'  => __( 'Opcje Motywu', 'antyki' ),
        'menu_title'  => __( 'Opcje Motywu', 'antyki' ),
        'menu_slug'   => 'theme-settings',
        'capability'  => 'edit_posts',
        'redirect'    => false
        )
    );
}
add_action( 'init', 'init_acf_options_page' );

function set_acf_save_point( $path ) {
    $path = get_stylesheet_directory() . '/inc/acf-json';
    return $path;
}
add_filter( 'acf/settings/save_json', 'set_acf_save_point' );

function set_acf_load_point( $paths ) {
    $paths[0] = get_stylesheet_directory() . '/inc/acf-json';
    return $paths;
}
add_filter( 'acf/settings/load_json', 'set_acf_load_point' );
