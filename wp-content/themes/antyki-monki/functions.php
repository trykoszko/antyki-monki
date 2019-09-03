<?php
include 'inc/theme-supports.php';
include 'inc/widgets.php';
include 'inc/cpt.php';
include 'inc/menus.php';
include 'inc/enqueues.php';
include 'inc/acf.php';
include 'inc/bootstrap-navwalker.php';

function wpse_modify_category_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        $query->set( 'post_type', 'product' );
    }
}
add_action( 'pre_get_posts', 'wpse_modify_category_query' );
