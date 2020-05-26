<?php

include 'inc/theme-supports.php';
include 'inc/widgets.php';
include 'inc/cpt.php';
include 'inc/menus.php';
include 'inc/enqueues.php';
if ( function_exists('acf_add_options_page') ) {
    include 'inc/acf.php';
}
include 'inc/walker.php';

function antyki_modify_category_query( $query ) {
    if ( !is_admin() && $query->is_main_query() && !is_page() ) {
        $query->set( 'post_type', 'product' );
    }
}
add_action( 'pre_get_posts', 'antyki_modify_category_query' );

