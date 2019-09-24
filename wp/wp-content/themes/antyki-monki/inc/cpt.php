<?php
function register_post_types() {
    register_post_type(
        'product',
        array(
            'description' => __( 'Antyki.', 'antyki' ),
            'public' => true,
            'supports' => array( 'title' ),
            'taxonomies'  => array( 'category' ),
            'labels' => array(
                'name' => _x( 'Antyki', 'post type general name', 'antyki' ),
                'singular_name' => _x( 'Antyk', 'post type singular name', 'antyki' ),
                'menu_name' => _x( 'Antyki', 'admin menu', 'antyki' ),
                'name_admin_bar' => _x( 'Antyk', 'add new on admin bar', 'antyki' ),
                'add_new' => _x( 'Dodaj nowy', 'product', 'antyki' ),
                'add_new_item' => __( 'Dodaj nowy', 'antyki' ),
                'new_item' => __( 'Nowy', 'antyki' ),
                'edit_item' => __( 'Edytuj', 'antyki' ),
                'view_item' => __( 'Zobacz', 'antyki' ),
                'all_items' => __( 'Zobacz wszystkie', 'antyki' ),
                'search_items' => __( 'Szukaj', 'antyki' ),
                'parent_item_colon' => __( 'Rodzic', 'antyki' ),
                'not_found' => __( 'Brak.', 'antyki' ),
                'not_found_in_trash' => __( 'Nie znaleziono w koszu.', 'antyki' ),
            ),
            'rewrite' => array(
                'slug' => 'antyki'
            ),
            'has_archive' => true,
            'menu_icon' => 'dashicons-admin-customizer',
            'show_in_rest' => true,
        )
    );
}
add_action( 'init', 'register_post_types' );

// function register_taxonomies() {
// }
// add_action( 'init', 'register_taxonomies' );
