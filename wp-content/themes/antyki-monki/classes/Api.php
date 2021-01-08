<?php
namespace Trykoszko\Antyki;

class Api {

    public function init() {

        // register_rest_route('antyki/v1', '/products', array(
        //     'methods' => 'GET',
        //     'callback' => [ $this, 'get_all_products' ]
        // ));

    }

    public function get_all_products( \WP_REST_Request $request ) {

        $req = $request->get_params();

        $query = [
            'post_type' => 'product',
            'posts_per_page' => 4,
            'fields' => 'ids'
        ];

        if ( $request->has_param( 'per_page' ) ) {
            $query['posts_per_page'] = $req['per_page'];
        }
        if ( $request->has_param( 'page' ) ) {
            $query['paged'] = $req['page'];
        }
        if ( $request->has_param( 'cats' ) ) {
            $query['categories'] = $req['cats'];
        }

        $posts = \Timber::get_posts( $query, '\Trykoszko\Antyki\ProductPost' );

        $post_array = [];
        foreach ( $posts as $post ) {
            $cats = [];
            $post_categories = \get_the_category( $post );
            if ( $post_categories ) {
                foreach ( $post_categories as $cat ) {
                    $cats[] = [
                        'id' => $cat->term_id,
                        'name' => $cat->name,
                        'url' => \get_term_link( $cat )
                    ];
                }
            }

            $gallery = [];
            $post_images = \get_field( 'product_gallery', $post );
            if ( $post_images ) {
                foreach ( $post_images as $img ) {
                    $gallery[] = [
                        'id' => $img['id'],
                        'alt' => $img['alt'],
                        'sizes' => [
                            'medium' => $img['sizes']['medium'],
                            'medium-width' => $img['sizes']['medium-width'],
                            'medium-height' => $img['sizes']['medium-height']
                        ]
                    ];
                }
            }

            $post_array[] = [
                'id' => $post->ID,
                'title' => \get_the_title( $post ),
                'productGallery' => $gallery,
                'url' => \get_permalink( $post ),
                'categories' => $cats,
                'price' => \get_post_meta( $post->ID, 'olx_attributes_price' )
            ];
        }

        wp_send_json_success( [
            'req_params' => $req,
            'products' => $post_array
        ] );

    }

}
