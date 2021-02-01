<?php

namespace Antyki\Plugin;

use Antyki\Container\Main as DIContainer;
use Antyki\Plugin\Cron as CRON;

/**
 * Main plugin class
 */
class Main
{
    public $container;
    public $olx;
    public $isAuthenticated;
    public $adminViews;
    public $ajax;
    public $cron;

    public function __construct(DIContainer $container)
    {
        $this->container = $container->getInstance();
        $this->olx = $this->container->get('Olx');
        $this->isAuthenticated = $this->container->call(
            function ($olxInstance) {
                return $olxInstance->auth->isAuthenticated;
            },
            [
                'olxInstance' => $this->olx,
            ]
        );
        $this->adminViews = $this->container->get('AdminViews');
        $this->ajax = $this->container->get('Ajax');
        $this->cron = new CRON($this->olx);
    }

    public function run()
    {
        if (!defined('ANTYKI_ADMIN_MENU_SLUG')) {
            define('ANTYKI_ADMIN_MENU_SLUG', 'wp-antyki-olx');
        }

        define('ANTYKI_NONCE_NAME', 'wp-antyki-nonce');
        define('REST_NAMESPACE', 'antyki');
        define('ANTYKI_CPT_PRODUCT', 'product');
        define('ANTYKI_CPT_PRODUCT_ALT', 'antyk');
        define('ANTYKI_CPT_PRODUCT_ALT_PLURAL', 'antyki');
        define('ANTYKI_CPT_CUSTOM_STATUS', 'sold');
        define('ANTYKI_CPT_CUSTOM_STATUS_LABEL', 'Sprzedane');

        $this->initHooks();
    }

    public function initHooks()
    {
        \add_filter('cron_schedules', [$this, 'createCustomCronSchedules']);

        \add_action('plugins_loaded', [$this, 'loadTextdomain']);
        \add_action('admin_menu', [$this, 'addToAdminMenu']);

        \add_action('wpAntykiOlx_cron_daily_8am', [$this->cron, 'run_daily_8am']);
        \add_action('wpAntykiOlx_cron_daily_10am', [$this->cron, 'run_daily_10am']);
        \add_action('wpAntykiOlx_cron_every_6_hours', [$this->cron, 'run_every_6_hours']);

        \add_action('admin_bar_menu', [$this, 'addOlxButtonsToAdminBar'], 100);
        \add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
        \add_action('admin_init', [$this, 'registerCustomSettings']);
        \add_filter('manage_product_posts_columns', [
            $this,
            'addCustomCptColumns',
        ]);
        \add_action(
            'manage_product_posts_custom_column',
            [$this, 'fillCustomCptColumns'],
            10,
            2
        );
        add_action('after_setup_theme', [$this, 'afterSetupTheme']);
        add_action('init', [$this, 'menusInit']);
        add_action('init', [$this, 'cptsInit']);
        add_action('init', [$this, 'postStatusesInit']);
        add_action('init', [$this, 'initAcfOptionsPage']);
        add_action('admin_menu', [$this, 'hideMenuForNonAdmins']);
        add_action('admin_footer', [$this, 'hideAdminBarForNonAdmins']);
        add_action('admin_footer-post.php', [
            $this,
            'addCustomPostStatusToSelect',
        ]);
        add_action( 'admin_menu', [$this, 'adminRedirectToPublishedProducts'] );
    }

    public function createCustomCronSchedules($schedules)
    {
        $schedules['every_six_hours'] = array(
            'interval' => 21600,
            'display'  => __( 'Every 6 hours' ),
        );
        return $schedules;
    }

    public function adminRedirectToPublishedProducts() {
        global $submenu;
        $submenu['edit.php?post_type=product'][5][2] = 'edit.php?post_type=product&post_status=publish';
    }

    public function enqueueAdminAssets()
    {
        wp_register_script(
            'olxAdmin',
            ANTYKI_ROOT_URL . 'scripts/admin.js',
            ['jquery'],
            '1.0.0',
            true
        );
        wp_localize_script('olxAdmin', 'olxData', [
            'ajaxUrl' => admin_url('/admin-ajax.php'),
            'security' => \wp_create_nonce(ANTYKI_NONCE_NAME),
        ]);
        wp_enqueue_script('olxAdmin');

        wp_enqueue_style(
            'olxAdmin',
            ANTYKI_ROOT_URL . 'style/style.css',
            [],
            '1.0.0'
        );
    }

    public function addOlxButtonsToAdminBar($admin_bar)
    {
        $admin_bar->add_node([
            'id' => 'olx-status',
            'title' => 'OLX',
            'href' => admin_url('/admin.php?page=wp-antyki-olx'),
        ]);
        $admin_bar->add_node([
            'parent' => 'olx-status',
            'id' => 'olx-show-dashboard',
            'title' => 'Panel',
            'href' => admin_url('/admin.php?page=wp-antyki-olx'),
        ]);
        $admin_bar->add_node([
            'parent' => 'olx-status',
            'id' => 'olx-refresh-stats',
            'title' => 'Odśwież statystyki',
            'href' => '#',
        ]);
    }

    public function loadTextdomain()
    {
        \load_textdomain(
            TEXTDOMAIN,
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    public function addToAdminMenu()
    {
        $menuIcon = 'dashicons-art';

        if ($this->isAuthenticated) {
            add_menu_page(
                __('Antyki - OLX', TEXTDOMAIN),
                __('Antyki - OLX', TEXTDOMAIN),
                'manage_options',
                ANTYKI_ADMIN_MENU_SLUG,
                [$this->adminViews, 'dashboardPage'],
                $menuIcon,
                200
            );
        } else {
            add_menu_page(
                __('Antyki - OLX - x', TEXTDOMAIN),
                __('Antyki - OLX - x', TEXTDOMAIN),
                'manage_options',
                ANTYKI_ADMIN_MENU_SLUG,
                [$this->adminViews, 'authPage'],
                $menuIcon,
                200
            );
        }

        // add separator
        global $menu;
        $menu[201] = ['', 'read', '', '', 'wp-menu-separator'];
    }

    public function registerCustomSettings()
    {
        add_option('olxClientId');
        register_setting('olxSettings', 'olxClientId');

        add_option('olxClientSecret');
        register_setting('olxSettings', 'olxClientSecret');

        add_option('olxState');
        register_setting('olxSettings', 'olxState');

        add_option('olxAccessToken');
        register_setting('olxAuthSettings', 'olxAccessToken');

        add_option('olxRefreshToken');
        register_setting('olxAuthSettings', 'olxRefreshToken');

        add_option('olxCode');
        register_setting('olxAuthSettings', 'olxCode');

        add_option('olxTokensLastRefresh');
        register_setting('olxAuthSettings', 'olxTokensLastRefresh');
    }

    public function addCustomCptColumns($columns)
    {
        $columns['productImage'] = __('Zdjęcie produktu', TEXTDOMAIN);
        $columns['productOlx'] = __('OLX', TEXTDOMAIN);

        unset($columns['date']);

        return $columns;
    }

    public function fillCustomCptColumns($column, $postId)
    {
        switch ($column) {
            case 'productImage':
                $this->adminViews->twig->render('adminColumns_productImage', [
                    'productImgUrl' => get_field('product_gallery', $postId)
                        ? get_field('product_gallery', $postId)[0]['sizes'][
                            'medium'
                        ]
                        : null,
                ]);
                break;
            case 'productOlx':
                $this->adminViews->twig->render('adminColumns_productOlx', [
                    'postId' =>
                        $postId,
                    'olxData' =>
                        get_field('olx_olx_data', $postId) ? json_decode(get_field('olx_olx_data', $postId)) : null,
                    'olxStats' =>
                        get_field('olx_advert_stats', $postId) ? json_decode(get_field('olx_advert_stats', $postId)) : null,
                    'isSold' =>
                        get_post_status($postId) == ANTYKI_CPT_CUSTOM_STATUS,
                    'validTo' =>
                        get_field('olx_valid_to', $postId),
                    'isStillValid' =>
                        get_field('olx_valid_to', $postId) >
                        date('Y-m-d H:i:s'),
                ]);
                break;
        }
    }

    public function postStatusesInit()
    {
        register_post_status(ANTYKI_CPT_CUSTOM_STATUS, [
            'label' => __(ANTYKI_CPT_CUSTOM_STATUS_LABEL, TEXTDOMAIN),
            'label_count' => _n_noop(
                ANTYKI_CPT_CUSTOM_STATUS_LABEL .
                    ' <span class="count">(%s)</span>',
                ANTYKI_CPT_CUSTOM_STATUS_LABEL .
                    ' <span class="count">(%s)</span>'
            ),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => false,
            'show_in_admin_status_list' => true,
            'internal' => true,
            'protected' => false,
            'publicly_queryable' => true,
        ]);
    }

    public function addCustomPostStatusToSelect()
    {
        global $post;
        $complete = '';
        $label = '';
        if ($post->post_type == 'product') {
            if ($post->post_status == ANTYKI_CPT_CUSTOM_STATUS) {
                $complete = ' selected=\"selected\"';
                $label =
                    '<span id=\"post-status-display\"> ' .
                    ANTYKI_CPT_CUSTOM_STATUS_LABEL .
                    '</span>';
            }
            echo '
                <script>
                    jQuery(document).ready(function($){
                        $("select#post_status").append("<option value=\"' .
                ANTYKI_CPT_CUSTOM_STATUS .
                '\" ' .
                $complete .
                '>' .
                ANTYKI_CPT_CUSTOM_STATUS_LABEL .
                '</option>");
                        $(".misc-pub-section label").append("' .
                $label .
                '");
                    });
                </script>
            ';
        }
    }

    public function afterSetupTheme()
    {
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ]);
        add_theme_support('post-formats', [
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
        ]);
        add_theme_support('menus');
    }

    public function menusInit()
    {
        register_nav_menus([
            'main-menu' => __('Menu', TEXTDOMAIN),
        ]);
    }

    public function cptsInit()
    {
        register_post_type(ANTYKI_CPT_PRODUCT, [
            'description' => __('Antyki', TEXTDOMAIN),
            'public' => true,
            'supports' => ['title'],
            'taxonomies' => ['category'],
            'labels' => [
                'name' => _x('Antyki', 'post type general name', TEXTDOMAIN),
                'singular_name' => _x(
                    'Antyk',
                    'post type singular name',
                    TEXTDOMAIN
                ),
                'menu_name' => _x('Antyki', 'admin menu', TEXTDOMAIN),
                'name_admin_bar' => _x(
                    'Antyk',
                    'add new on admin bar',
                    TEXTDOMAIN
                ),
                'add_new' => _x('Dodaj nowy', ANTYKI_CPT_PRODUCT, TEXTDOMAIN),
                'add_new_item' => __('Dodaj nowy', TEXTDOMAIN),
                'new_item' => __('Nowy', TEXTDOMAIN),
                'edit_item' => __('Edytuj', TEXTDOMAIN),
                'view_item' => __('Zobacz', TEXTDOMAIN),
                'all_items' => __('Zobacz wszystkie', TEXTDOMAIN),
                'search_items' => __('Szukaj', TEXTDOMAIN),
                'parent_item_colon' => __('Rodzic', TEXTDOMAIN),
                'not_found' => __('Brak.', TEXTDOMAIN),
                'not_found_in_trash' => __(
                    'Nie znaleziono w koszu.',
                    TEXTDOMAIN
                ),
            ],
            'rewrite' => [
                'slug' => TEXTDOMAIN,
            ],
            'has_archive' => true,
            'menu_icon' => 'dashicons-admin-customizer',
            'show_in_rest' => true,
            'rest_base' => ANTYKI_CPT_PRODUCT_ALT_PLURAL,
            'show_in_graphql' => true,
            'graphql_single_name' => ANTYKI_CPT_PRODUCT_ALT,
            'graphql_plural_name' => ANTYKI_CPT_PRODUCT_ALT_PLURAL,
        ]);
    }

    public function initAcfOptionsPage()
    {
        acf_add_options_page([
            'page_title' => __('Opcje OLX', TEXTDOMAIN),
            'menu_title' => __('Opcje OLX', TEXTDOMAIN),
            'menu_slug' => 'theme-options',
            'capability' => 'edit_posts',
            'redirect' => false,
        ]);
        acf_add_options_page([
            'page_title' => __('Opcje Motywu', TEXTDOMAIN),
            'menu_title' => __('Opcje Motywu', TEXTDOMAIN),
            'menu_slug' => 'theme-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
        ]);
    }

    public function hideMenuForNonAdmins()
    {
        if (current_user_can('editor')) {
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

    public function hideAdminBarForNonAdmins()
    {
        if (current_user_can('editor')) { ?>
            <style>
                #wp-admin-bar-new-content,
                #wp-admin-bar-comments,
                #wp-admin-bar-wp-logo {
                    display: none;
                }
            </style>
        <?php }
    }
}
