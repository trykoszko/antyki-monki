<?php
namespace Trykoszko\Antyki;

use \Timber as Timber;

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
            return 'No Timber installed.';
        }
    );
    return;
}


class Antyki extends Timber\Site {

    public $timber;

    public function __construct() {

        define( 'TEXTDOMAIN', 'antyki' );
        define( 'REST_NAMESPACE', 'antyki' );

        define( 'ANTYKI_CPT_PRODUCT', 'product' );
        define( 'ANTYKI_CPT_PRODUCT_ALT', 'antyk' );
        define( 'ANTYKI_CPT_PRODUCT_ALT_PLURAL', 'antyki' );

        $this->init_hooks();

        parent::__construct();

    }

    public function init_hooks() {

        add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ] );
        add_action( 'init', [ $this, 'menus_init' ] );
        add_action( 'init', [ $this, 'cpts_init' ] );
        add_action( 'init', [ $this, 'post_statuses_init' ] );

        add_action( 'init', [ $this, 'acf_init_options_page' ] );
        add_filter( 'acf/settings/save_json', [ $this, 'acf_set_save_point' ] );
        add_filter( 'acf/settings/load_json', [ $this, 'acf_set_load_point' ] );

        add_action( 'admin_menu', [ $this, 'hide_menu_for_editor' ] );
        add_action( 'admin_footer', [ $this, 'hide_admin_bar_for_editor' ] );
        add_action( 'admin_footer-post.php', [ $this, 'jc_append_post_status_list' ] );

    }

    public function post_statuses_init() {

        register_post_status( 'sold', array(
            'label'                     => _x( 'Sprzedane', 'product' ),
            'label_count'               => _n_noop( 'Sprzedane <span class="count">(%s)</span>', 'Sprzedane <span class="count">(%s)</span>' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
        ) );

    }

    function jc_append_post_status_list(){
        global $post;
        $complete = '';
        $label = '';
        if($post->post_type == 'product'){
            if($post->post_status == 'sold'){
                $complete = ' selected=\"selected\"';
                $label = '<span id=\"post-status-display\"> Sprzedany</span>';
            }
            echo '
                <script>
                    jQuery(document).ready(function($){
                        $("select#post_status").append("<option value=\"sold\" '.$complete.'>Sprzedany</option>");
                        $(".misc-pub-section label").append("'.$label.'");
                    });
                </script>
            ';
        }
    }

    public function after_setup_theme() {

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption' ) );
        add_theme_support( 'post-formats', array( 'image', 'video', 'quote', 'link', 'gallery', 'audio' ));
        add_theme_support( 'menus' );

    }

    public function menus_init() {

        register_nav_menus(
            array(
                'main-menu' => __( 'Menu', TEXTDOMAIN )
            )
        );

    }

    public function cpts_init() {

        register_post_type(
            ANTYKI_CPT_PRODUCT,
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
                    'add_new' => _x( 'Dodaj nowy', ANTYKI_CPT_PRODUCT, TEXTDOMAIN ),
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
                'rest_base' => ANTYKI_CPT_PRODUCT_ALT_PLURAL,
                'show_in_graphql' => true,
                'graphql_single_name' => ANTYKI_CPT_PRODUCT_ALT,
                'graphql_plural_name' => ANTYKI_CPT_PRODUCT_ALT_PLURAL
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

        if ( current_user_can('editor') ) {

            remove_menu_page('index.php');
            remove_menu_page('jetpack');
            remove_menu_page('edit.php');
            remove_menu_page('edit.php?post_type=page');
            remove_menu_page('edit-comments.php');
            remove_menu_page('themes.php');
            remove_menu_page('plugins.php');
            remove_menu_page('profile.php');
            remove_menu_page('tools.php');
            remove_menu_page('options-general.php');

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

}

$site = new Antyki();
