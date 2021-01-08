<?php
$context = Timber::context();
$posts = Timber::get_posts( [
    'post_type' => 'product'
], '\Trykoszko\Antyki\ProductPost' );

$context['posts'] = $posts;

$seo_title = is_home() ? get_bloginfo('title') : get_the_title() . ' - ' . get_bloginfo('title');

$cat = get_queried_object();
$current_cat_name = '';
$current_cat_id = '';
if ($cat && property_exists($cat, 'taxonomy') && $cat->taxonomy === 'category') {
    $current_cat_name = $cat->name;
    $current_cat_id = $cat->term_id;
}
$all_cats = get_categories(array(
    'orderby' => 'count',
    'order' => 'DESC'
));
$all_cats_count = count($all_cats);

$product_price_meta_field_name = 'olx_attributes_price';
$items_price_min = $site->get_end_meta_val( 'min', $product_price_meta_field_name );
$items_price_max = $site->get_end_meta_val( 'max', $product_price_meta_field_name );

$product_filters = [
    // [
    //     'title' => __( 'Kategoria', 'antyki' ),
    //     'slug' => 'category',
    //     'type' => 'checkboxes', // checkboxes | select | range
    //     'choices' => []
    // ],
    [
        'title' => __( 'Kategoria', 'antyki' ),
        'slug' => 'category',
        'type' => 'checkboxes',
        'choices' => [
            // @todo get all cats and pass here
            'meble' => 'Meble',
            'stoly-i-krzesla' => 'Stoły i krzesła',
            'szafy-i-komody' => 'Szafy i komody'
        ]
    ],
    [
        'title' => __( 'Materiał', 'antyki' ),
        'slug' => 'material',
        'type' => 'checkboxes',
        'choices' => [
            // @todo get all metas `material` and pass here
            'wood' => 'drewno',
            'steel' => 'stal',
            'iron' => 'zelazo'
        ]
    ],
    [
        'title' => __( 'Cena', 'antyki' ),
        'slug' => 'price',
        'type' => 'range',
        'from' => $items_price_min,
        'to' => $items_price_max
    ]
];

$context = array_merge( $context, [
    'seoTitle' => $seo_title,
    'searchQuery' =>  get_search_query(),
    'isSearch' => is_search(),
    'isHome' => is_home(),
    'currentCatName' => $current_cat_name,
    'currentCatId' => $current_cat_id,
    'allCats' => $all_cats,
    'allCatsCount' => $all_cats_count,
    'filters' => $product_filters,
    'isSingularProduct' => is_singular('product')
] );

Timber::render( 'index.twig', $context );

// Timber::render( 'blank.twig' );
