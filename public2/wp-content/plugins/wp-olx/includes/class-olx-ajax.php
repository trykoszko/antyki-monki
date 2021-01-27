<?php

/**
 * OLX cache class
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 */

/**
 * Class with Ajax methods
 *
 * @since 1.0.0
 */
class Olx_Ajax {

	/**
	 * Register AJAX action for adding OLX advert
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function ajax_add_advert() {

		// arguments from AJAX request
		$args = $_REQUEST;
		$product_id = $args['product_id'];

		// create Visbook instance, add advert
        $olx = new Olx();
        $advert = $olx->add_advert( $product_id );

		// return json response
        echo json_encode( $advert );
        wp_die();

	}

}
