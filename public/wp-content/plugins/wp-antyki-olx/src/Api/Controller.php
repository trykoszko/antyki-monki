<?php

/**
 * @OA\Info(title="My First API", version="0.1")
 */

namespace Antyki\Api;

class Controller {

    public static function prepareProduct($postId, $isTeaser = false)
    {
        $post = get_post($postId);
        if ($post->post_type === ANTYKI_CPT_PRODUCT) {
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
                'id' => is_object($post) ? $post->ID : $post,
                'title' => get_the_title($post),
                'status' => get_post_status($post),
                'slug' => get_post_field('post_name', $post),
                'date' => get_post_datetime($post),
                'cats' => $categories,
                'acf' => get_fields($post)
            ];
        } else {
            $product = [
                'success' => false,
                'message' => "Post $postId is not of `" . ANTYKI_CPT_PRODUCT . "` post type"
            ];
        }
        return $product;
    }

    public static function prepareCategory($cat)
    {
        if (!is_object($cat)) {
            $cat = get_term($cat);
        }
        $items = [];
        $products = get_posts([
            'post_type' => ANTYKI_CPT_PRODUCT,
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields' => 'ids'
        ]);
        if ($products) {
            foreach ($products as $product) {
                $items[] = self::prepareProduct($product);
            }
        }
        $category = [
            'id' => $cat->term_id,
            'name' => $cat->name,
            'slug' => $cat->slug,
            'count' => $cat->count,
            'desc' => $cat->description,
            'acf' => get_fields($cat),
            'items' => $items
        ];
        return $category;
    }

    /**
     * @OA\Get(
     *     path="/wp-json/antyki/v1/product",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function getSingleProduct($request)
    {
        $postId = $request->get_param('id');
        if (!is_numeric($postId)) {
            $posts = get_posts([
                'name' => $postId,
                'post_type' => ANTYKI_CPT_PRODUCT,
                'post_status' => 'all',
                'numberposts' => 1
            ]);
            $postId = !empty($posts) ? $posts[0]->ID : null;
        }
        if ($postId) {
            $product = self::prepareProduct($postId);
        } else {
            $product = [
                'success' => false,
                'message' => "Post $postId not found"
            ];
        }
        echo json_encode($product);
        die();
    }

    public function getAllProducts()
    {
        $products = [];
        $posts = get_posts([
            'post_type' => ANTYKI_CPT_PRODUCT,
            // 'numberposts' => -1,
            'numberposts' => 5,
            'post_status' => 'publish',
            'fields' => 'ids'
        ]);
        foreach ($posts as $postId) {
            $products[] = self::prepareProduct($postId);
        }
        echo json_encode($products);
        die();
    }

    public function getAllProductSlugs()
    {
        $posts = get_posts([
            'post_type' => ANTYKI_CPT_PRODUCT,
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields' => 'ids'
        ]);
        $products = [];
        if ($posts) {
            foreach ($posts as $postId) {
                $post = get_post($postId);
                $products[$postId] = $post->post_name;
            }
        }
        echo json_encode($products);
        die();
    }

    public function getSingleCategory($request)
    {
        $termId = $request->get_param('id');
        if ($termId) {
            $cat = get_term($termId);
            $category = self::prepareCategory($cat);
        } else {
            $category = [
                'success' => false,
                'message' => "Category $termId not found"
            ];
        }
        echo json_encode($category);
        die();
    }

    public function getAllCategories()
    {
        $categories = [];
        $cats = get_terms([
            'taxonomy' => 'category',
            'hide_empty' => false
        ]);
        foreach ($cats as $cat) {
            $categories[] = self::prepareCategory($cat);
        }
        echo json_encode($categories);
        die();
    }

    public function getAllPages()
    {
        $pages = [];
        $pagesArr = get_posts([
            'post_type' => 'page',
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields' => 'ids'
        ]);
        foreach ($pagesArr as $pageId) {
            $post = get_post($pageId);
            $pages[] = [
                'id' => $pageId,
                'slug' => $post->post_name,
                'title' => get_the_title($pageId),
                'content' => get_the_content($pageId),
                'acf' => get_fields($pageId)
            ];
        }
        echo json_encode($pages);
        die();
    }

    public function getAllOptions()
    {
        $options = get_fields('option');
        $settings = self::getGlobalSettings();
        $menus = self::getAllMenus();
        echo json_encode([
            'themeOptions' => $options,
            'menus' => $menus,
            'globalSettings' => $settings
        ]);
        die();
    }

    public static function getAllMenus()
    {
        $menus = get_registered_nav_menus();
        $menusWithItems = [];
        if ($menus) {
            foreach ($menus as $menuSlug => $menuName) {
                $menu = [
                    'name' => $menuName,
                    'slug' => $menuSlug
                ];
                $items = wp_get_nav_menu_items($menuName);
                if ($items) {
                    foreach ($items as $item) {
                        $menuItemUrl = str_replace(
                            'https://antyki.sors.smarthost.pl',
                            '',
                            str_replace(
                                'https://antyki.sors.smarthost.pl.devlocal',
                                '',
                                $item->url
                            )
                        );
                        $menu['items'][] = [
                            'id' => $item->ID,
                            'title' => $item->title,
                            'target' => $item->target,
                            'url' => $menuItemUrl,
                            'order' => $item->menu_order,
                            'parent_id' => $item->menu_item_parent
                        ];
                    }
                }
                $menusWithItems[] = $menu;
            }
        }
        return $menusWithItems;
    }

    public static function getGlobalSettings()
    {
        $allOptions = wp_load_alloptions();
        $itemsToGet = [
            'blogname', 'blogdescription', 'admin_email'
        ];
        $optionsToReturn = [];
        foreach ($allOptions as $optionName => $optionValue) {
            if (in_array($optionName, $itemsToGet)) {
                $optionsToReturn[$optionName] = $optionValue;
            }
        }
        return $optionsToReturn;
    }

}