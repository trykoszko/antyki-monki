<?php
function antyki_scripts() {
	wp_enqueue_style( 'antyki-style', get_stylesheet_uri() );
	wp_enqueue_script( 'antyki-navigation', get_template_directory_uri() . '/assets/js/app.js', array(), rand(0, 9999), true );
}
add_action( 'wp_enqueue_scripts', 'antyki_scripts' );
