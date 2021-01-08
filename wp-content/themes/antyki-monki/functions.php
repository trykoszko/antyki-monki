<?php
namespace Trykoszko\Antyki;

$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
    require_once $composer_autoload;
    $timber = new \Timber\Timber();
}

if ( ! class_exists( 'Timber' ) ) {
    add_action(
        'admin_notices',
        function() {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
        }
    );
    add_filter(
        'template_include',
        function( $template ) {
            return get_stylesheet_directory() . '/static/no-timber.html';
        }
    );
    return;
}

\Timber::$dirname = array( 'views' );
\Timber::$autoescape = false;

class Antyki extends \Timber\Site {

    public $timber;

    public function __construct() {

        define( 'TEXTDOMAIN', 'antyki' );
        define( 'REST_NAMESPACE', 'antyki' );

        $this->init_hooks();

        parent::__construct();

    }

    public function init_hooks() {

        $api = new Api();

        add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ] );
        add_action( 'init', [ $this, 'menus_init' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueues_init' ] );
        add_action( 'init', [ $this, 'cpts_init' ] );
        // add_action( 'widgets_init', [ $this, 'widgets_init' ] );

        // API
        add_action( 'rest_api_init', [ $api, 'init' ] );

        add_action( 'init', [ $this, 'acf_init_options_page' ] );
        add_filter( 'acf/settings/save_json', [ $this, 'acf_set_save_point' ] );
        add_filter( 'acf/settings/load_json', [ $this, 'acf_set_load_point' ] );

        add_filter( 'timber/twig', [ $this, 'add_to_twig' ] );
        add_filter( 'timber/context', [ $this, 'add_to_context' ] );

        add_action( 'admin_menu', [ $this, 'hide_menu_for_editor' ] );
        add_action( 'admin_footer', [ $this, 'hide_admin_bar_for_editor' ] );

        // AJAX
        // ajax_get_all_posts
        add_action( 'wp_ajax_nopriv_get_all_posts', [ $this, 'ajax_get_all_posts' ] );
        add_action( 'wp_ajax_get_all_posts', [ $this, 'ajax_get_all_posts' ] );
        add_action( 'wp_ajax_nopriv_get_card_images', [ $this, 'ajax_get_card_images' ] );
        add_action( 'wp_ajax_get_card_images', [ $this, 'ajax_get_card_images' ] );

    }

    public function add_to_twig( $twig ) {

        $twig->addExtension( new \Twig\Extension\StringLoaderExtension() );
        // $twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
        return $twig;

    }

    public function add_to_context( $context ) {
        $context = array_merge( $context, [
            'assetUrl' => get_stylesheet_directory_uri() . '/front/static',
            'isHome' => is_home() ? get_bloginfo('title') : get_the_title() . ' - ' . get_bloginfo('title'),
            'headerNav' => new \Timber\Menu( 'header-menu' ),
            'options' => get_fields('options'),
            'footerNav' => new \Timber\Menu( 'footer-menu' )
        ] );

        return $context;
    }

    public function after_setup_theme() {

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption' ) );
        add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'gallery', 'audio' ));
        add_theme_support( 'menus' );

    }

    public function widgets_init() {

        register_sidebar( array(
            'name'          => esc_html__( 'Sidebar', TEXTDOMAIN ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', TEXTDOMAIN ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );

    }

    public function menus_init() {

        register_nav_menus(
            array(
                'header-menu' => __( 'Header Menu', TEXTDOMAIN ),
                'footer-menu' => __( 'Footer Menu', TEXTDOMAIN )
            )
        );

    }

    public function enqueues_init() {

        wp_enqueue_style( 'antyki-style', get_template_directory_uri() . '/front/static/css/main.css' );

        wp_register_script( 'antyki-script', get_template_directory_uri() . '/front/static/js/app.js', [ 'wp-i18n' ], rand( 0, 99999 ), true );
        wp_localize_script( 'antyki-script', 'wpData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'apiUrl' => get_rest_url(null, 'antyki/v1/')
        ] );
        wp_enqueue_script( 'antyki-script' );

    }

    public function cpts_init() {

        register_post_type(
            'product',
            array(
                'description' => __( 'Antyki.', TEXTDOMAIN ),
                'public' => true,
                'supports' => array( 'title' ),
                'taxonomies'  => array( 'category' ),
                'labels' => array(
                    'name' => _x( 'Antyki', 'post type general name', TEXTDOMAIN ),
                    'singular_name' => _x( 'Antyk', 'post type singular name', TEXTDOMAIN ),
                    'menu_name' => _x( 'Antyki', 'admin menu', TEXTDOMAIN ),
                    'name_admin_bar' => _x( 'Antyk', 'add new on admin bar', TEXTDOMAIN ),
                    'add_new' => _x( 'Dodaj nowy', 'product', TEXTDOMAIN ),
                    'add_new_item' => __( 'Dodaj nowy', TEXTDOMAIN ),
                    'new_item' => __( 'Nowy', TEXTDOMAIN ),
                    'edit_item' => __( 'Edytuj', TEXTDOMAIN ),
                    'view_item' => __( 'Zobacz', TEXTDOMAIN ),
                    'all_items' => __( 'Zobacz wszystkie', TEXTDOMAIN ),
                    'search_items' => __( 'Szukaj', TEXTDOMAIN ),
                    'parent_item_colon' => __( 'Rodzic', TEXTDOMAIN ),
                    'not_found' => __( 'Brak.', TEXTDOMAIN ),
                    'not_found_in_trash' => __( 'Nie znaleziono w koszu.', TEXTDOMAIN ),
                ),
                'rewrite' => array(
                    'slug' => TEXTDOMAIN
                ),
                'has_archive' => true,
                'menu_icon' => 'dashicons-admin-customizer',
                'show_in_rest' => true,
                'rest_base' => 'antyki',
                'show_in_graphql' => true,
                'graphql_single_name' => 'antyk',
                'graphql_plural_name' => 'antyki'
            )
        );

    }

    function acf_init_options_page() {

        acf_add_options_page(
            array(
            'page_title'  => __( 'Opcje OLX', TEXTDOMAIN ),
            'menu_title'  => __( 'Opcje OLX', TEXTDOMAIN ),
            'menu_slug'   => 'theme-options',
            'capability'  => 'edit_posts',
            'redirect'    => false
            )
        );
        acf_add_options_page(
            array(
            'page_title'  => __( 'Opcje Motywu', TEXTDOMAIN ),
            'menu_title'  => __( 'Opcje Motywu', TEXTDOMAIN ),
            'menu_slug'   => 'theme-settings',
            'capability'  => 'edit_posts',
            'redirect'    => false
            )
        );

    }

    function acf_set_save_point( $path ) {

        $path = get_stylesheet_directory() . '/inc/acf-json';
        return $path;

    }

    function acf_set_load_point( $paths ) {

        $paths[0] = get_stylesheet_directory() . '/inc/acf-json';
        return $paths;

    }

    public function hide_menu_for_editor() {

        if (current_user_can('editor')) {
            remove_menu_page('index.php');                  //Dashboard
            remove_menu_page('jetpack');                    //Jetpack*
            remove_menu_page('edit.php');                   //Posts
            // remove_menu_page( 'upload.php' );                 //Media
            remove_menu_page('edit.php?post_type=page');    //Pages
            remove_menu_page('edit-comments.php');          //Comments
            remove_menu_page('themes.php');                 //Appearance
            remove_menu_page('plugins.php');                //Plugins
            // remove_menu_page( 'users.php' );                  //Users
            remove_menu_page('profile.php');                  //Profile
            remove_menu_page('tools.php');                  //Tools
            remove_menu_page('options-general.php');        //Settings
        }

    }

    public function hide_admin_bar_for_editor() {

        if (current_user_can('editor')) :
            ?>
                <style>
                    #wp-admin-bar-new-content,
                    #wp-admin-bar-comments,
                    #wp-admin-bar-wp-logo {
                        display: none;
                    }
                </style>
            <?php
        endif;

    }

    public function get_end_meta_val( $type = 'max', $key ) {

        global $wpdb;
        $sql = "SELECT " . $type . "( cast( meta_value as UNSIGNED ) ) FROM {$wpdb->postmeta} WHERE meta_key='%s'";
        $query = $wpdb->prepare( $sql, $key );

        return $wpdb->get_var( $query );

    }

    public static function getSingleCardImageHtml($img) {
        echo '<figure class="c-card-gallery__img c-card-gallery__img--' . $img['orientation'] . ' c-card-gallery__img--' . $img['name'] . '">
            <img class="c-img o-object-fit-' . $img['object_fit'] . '" src="' . $img['src'] . '" alt="' . $img['alt'] . '" />
        </figure>';
    }

    public function generate_product_images_html( $post_id ) {

        $gallery = get_field('product_gallery', $post_id);
        $first_img = $gallery[0]['sizes']['thumbnail'];
        $second_img = $gallery[1]['sizes']['thumbnail'];

        $html = '';

        if ($first_img) {
            $first_img_aspect_ratio = round($gallery[0]['sizes']['medium-width'] / $gallery[0]['sizes']['medium-height'], 2);
            $first_img_array = [
                'exists' => true,
                'name' => 'first',
                'aspect_ratio' => $first_img_aspect_ratio,
                'object_fit' => $first_img_aspect_ratio === 1.78 || $first_img_aspect_ratio === 0.56 ? 'contain' : 'cover',
                'orientation' => $first_img_aspect_ratio > 1 ? 'landscape' : 'portrait',
                'src' => $first_img,
                'alt' => $gallery[0]['alt']
            ];
            $html .= self::getSingleCardImageHtml($first_img_array);
        }

        if ($second_img) {
            $second_img_aspect_ratio = round($gallery[0]['sizes']['medium-width'] / $gallery[0]['sizes']['medium-height'], 2);
            $second_img_array = [
                'exists' => true,
                'name' => 'second',
                'aspect_ratio' => $second_img_aspect_ratio,
                'object_fit' => $second_img_aspect_ratio === 1.78 || $second_img_aspect_ratio === 0.56 ? 'contain' : 'cover',
                'orientation' => $second_img_aspect_ratio > 1 ? 'landscape' : 'portrait',
                'src' => $second_img,
                'alt' => $gallery[1]['alt']
            ];
            $html .= self::getSingleCardImageHtml($second_img_array);
        }

        return $html;

    }

    public function ajax_get_card_images() {

        $data = $_REQUEST;

        $post_id = $data['post_id'];

        $html = $this->generate_product_images_html( $post_id );

        return $html;

    }

    public function ajax_get_all_posts() {

        $data = $_REQUEST;

        $query = [
            'post_type' => 'product',
            'posts_per_page' => 4
        ];

        if ( isset( $data['page'] ) && $data['page'] !== 0 ) {
            $query['paged'] = $page;
        }

        $posts = \Timber::get_posts( $query, '\Trykoszko\Antyki\ProductPost' );

        wp_send_json_success( $posts );

    }

}

$site = new Antyki();
