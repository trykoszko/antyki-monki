<?php
function antyki_scripts() {
	wp_enqueue_style( 'antyki-style', get_template_directory_uri() . '/front/static/css/main.css' );
	wp_enqueue_script( 'antyki-script', get_template_directory_uri() . '/front/static/js/app.js', array(), rand(0, 9999), true );
}
add_action( 'wp_enqueue_scripts', 'antyki_scripts' );
