<?php

/**
 * OLX helper functions
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 */

/**
 * Function that gets products where adverts are active and ending
 *
 * @since 1.0.0
 */
function olx_get_products_with_ending_adverts() {

    $product_id = null;

    $products = get_posts( array(
        'post_type' => 'product',
        'numberposts' => '-1',
        'fields' => 'ids'
    ) );

    if ( $products ) {

        // get only products with active adverts
        $products_with_active_adverts = array_filter( $products_with_adverts, function( $product_id ) {
            $advert = get_field( 'olx_olx_data', $product_id );
            if ( $advert ) {
                $advert_data = json_decode( $advert );
                
            }
            return $advert->status == 'active';
        } );

        // // get only active adverts
        // $active_adverts = array_filter( $adverts, function( $item ) {
        //     return $item->status == 'active';
        // } );
        // // sort by dates ASC
        // usort( $active_adverts, function( $a, $b ) {
        //     return $a->valid_to > $b->valid_to;
        // } );
        // // return filtered and sorted products
        // $data = array_slice( $active_adverts, 0, 9 );

    }

    // if ( $products ) {
    //     $product_id = $products[0];
    // }

    // return $product_id;

}
