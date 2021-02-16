<?php

namespace Antyki\Api;

class Controller {

    public function getSingleProduct($request)
    {
        try {
            $post_id = $request->get_param('id');
            if (!$post_id) {
                throw new \Exception("Post $post_id not found");
            }

            $post = get_post($post_id);
            if ($post->post_type != ANTYKI_CPT_PRODUCT) {
                throw new \Exception("Post $post_id is not of `" . ANTYKI_CPT_PRODUCT . "` post type");
            }

            $categories = [];
            $cats = get_the_category($post);
            foreach ($cats as $cat) {
                $categories[] = [
                    'id' => $cat->term_id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'items' => $cat->count
                ];
            }

            $product = [
                'id' => $post,
                'title' => get_the_title($post),
                'status' => get_post_status($post),
                'slug' => get_post_field('post_name', $post),
                'date' => get_post_datetime($post),
                'cats' => $categories,
                'acf' => get_fields($post)
            ];

            echo json_encode($product);
        } catch (\Exception $e) {
            echo json_encode([
                'action' => 'getSingleProduct',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getAllProducts()
    {
        $products = [];

        $posts = get_posts([
            'post_type' => ANTYKI_CPT_PRODUCT,
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields' => 'ids'
        ]);

        foreach ($posts as $post) {
            $cats = get_the_category($post);
            $categories = [];
            foreach ($cats as $cat) {
                $categories[] = [
                    'id' => $cat->term_id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'items' => $cat->count
                ];
            }
            $products[] = [
                'id' => $post,
                'title' => get_the_title($post),
                'status' => get_post_status($post),
                'slug' => get_post_field('post_name', $post),
                'date' => get_post_datetime($post),
                'cats' => $categories,
                'acf' => get_fields($post)
            ];
        }

        echo json_encode($products);
    }

}
