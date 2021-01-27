<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/admin
 * @author     Michal Trykoszko <trykoszkom@gmail.com>
 */
class Wp_Olx_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'front/static/css/main.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        wp_register_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'front/static/js/app.js',
            array('jquery'),
            $this->version,
            true
        );

        wp_localize_script(
            $this->plugin_name,
            'wpTranslates',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'ajaxActions' => array(
                    'addProduct' => 'ajax_add_advert',
                    'updateProduct' => 'ajax_update_advert',
                    'updateAdvert' => 'ajax_renew_advert'
                ),
                'prompt_areYouSure' => __('Czy jesteś pewny? Tej akcji nie będzie można cofnąć!', 'wp-olx')
            )
        );

        wp_enqueue_script($this->plugin_name);
    }

    /**
     * Register the admin page in admin area menu.
     *
     * @since	1.0.0
     */
    public function admin_page_menu_item()
    {

        /**
         * Adds a menu page for the plugin
         *
         * @since 1.0.0
         */
        add_menu_page(
            __('OLX', 'wp-olx'),
            __('OLX', 'wp-olx'),
            'edit_posts',
            'wp-olx',
            array($this, 'admin_page_display')
        );

        // show pages if authorized
        if (Olx::has_tokens()) {

            /**
             * Adds a sub-menu page for the plugins global settings page
             *
             * @since 1.0.0
             */
            add_submenu_page(
                'wp-olx',
                __('Ustawienia', 'wp-olx'),
                __('Ustawienia', 'wp-olx'),
                'manage_options',
                'wp-olx-general-settings',
                array($this, 'admin_page_display_general_settings')
            );
        } else {

            /**
             * Adds a sub-menu page for the plugins settings page
             *
             * @since 1.0.0
             */
            add_submenu_page(
                'wp-olx',
                __('Ustawienia API', 'wp-olx'),
                __('Ustawienia API', 'wp-olx'),
                'manage_options',
                'wp-olx-settings',
                array($this, 'admin_page_display_settings')
            );

            /**
             * Adds a sub-menu page for the plugins authorization page
             *
             * @since 1.0.0
             */
            add_submenu_page(
                'wp-olx',
                __('Autoryzacja API', 'wp-olx'),
                __('Autoryzacja API', 'wp-olx'),
                'manage_options',
                'wp-olx-auth',
                array($this, 'admin_page_display_auth')
            );
        }
    }

    /**
     * Register action links for plugin in wp-admin/plugins page
     *
     * @since 1.0.0
     */
    function add_action_links($links)
    {

        if (Olx::has_tokens()) {
            $links[] = '<a href="' . admin_url('admin.php?page=wp-olx') . '">' . __('Ogłoszenia', 'wp-olx') . '</a>';
        } else {
            $links[] = '<a href="' . admin_url('admin.php?page=wp-olx-auth') . '">' . __('Autoryzacja', 'wp-olx') . '</a>';
        }

        return $links;
    }

    /**
     * Register custom plugin settings
     *
     * @since 1.0.0
     */
    public function register_settings()
    {

        // olx settings

        /**
         * OLX Client ID
         * @since 1.0.0
         */
        add_option('_olx_client_id');
        register_setting('olx_settings', '_olx_client_id');

        /**
         * OLX Client Secret
         * @since 1.0.0
         */
        add_option('_olx_client_secret');
        register_setting('olx_settings', '_olx_client_secret');

        /**
         * OLX State
         * @since 1.0.0
         */
        add_option('_olx_state');
        register_setting('olx_settings', '_olx_state');

        /**
         * OLX Access Token
         * @since 1.0.0
         */
        add_option('_olx_access_token');
        register_setting('olx_settings', '_olx_access_token');

        /**
         * OLX Refresh Token
         * @since 1.0.0
         */
        add_option('_olx_refresh_token');
        register_setting('olx_settings', '_olx_refresh_token');


        // olx auth settings

        /**
         * OLX Authorization Token
         * @since 1.0.0
         */
        add_option('_olx_authorization_token');
        register_setting('olx_auth_settings', '_olx_authorization_token');

        /**
         * OLX Authorization Tokens last refresh
         * @since 1.0.0
         */
        add_option('_olx_tokens_last_refresh');
        register_setting('olx_auth_settings', '_olx_tokens_last_refresh');


        // general settings

        /**
         * OLX Cache setting
         * @since 1.0.0
         */
        add_option('_olx_enable_cache');
        register_setting('olx_general_settings', '_olx_enable_cache');
    }

    /**
     * Method to display admin area error messages as notifications
     *
     * @since 1.0.0
     */
    public function settings_messages($error_message)
    {
        switch ($error_message) {
            case '1':
                $message = __('There was an error editing the setting.', 'wp-olx');
                $err_code = esc_attr('olx_client_id');
                $setting_field = 'olx_client_id';
                break;
        }

        add_settings_error(
            $setting_field,
            $err_code,
            $message,
            'error'
        );
    }

    /**
     * Callback function for displaying admin page
     *
     * @since 1.0.0
     */
    public function admin_page_display()
    {

        if (Olx::has_tokens()) {
            require_once plugin_dir_path(__FILE__) . 'partials/wp-olx-admin-display.php';
        } else {
            require_once plugin_dir_path(__FILE__) . 'partials/wp-olx-admin-unauthorized.php';
        }
    }

    /**
     * Callback function for displaying admin settings page
     *
     * @since 1.0.0
     */
    public function admin_page_display_settings()
    {

        if (isset($_GET['error_message'])) {
            add_action('admin_notices', array($this, ''));
            do_action('admin_notices', $_GET['error_message']);
        }

        require_once plugin_dir_path(__FILE__) . 'partials/wp-olx-admin-settings.php';
    }

    /**
     * Callback function for displaying admin settings page
     *
     * @since 1.0.0
     */
    public function admin_page_display_general_settings()
    {

        if (isset($_GET['error_message'])) {
            add_action('admin_notices', array($this, ''));
            do_action('admin_notices', $_GET['error_message']);
        }

        require_once plugin_dir_path(__FILE__) . 'partials/wp-olx-admin-general-settings.php';
    }

    /**
     * Callback function for displaying admin settings page
     *
     * @since 1.0.0
     */
    public function admin_page_display_auth()
    {

        if (isset($_GET['error_message'])) {
            add_action('admin_notices', array($this, ''));
            do_action('admin_notices', $_GET['error_message']);
        }

        require_once plugin_dir_path(__FILE__) . 'partials/wp-olx-admin-auth.php';
    }

    /**
     * Modify product cpt admin columns
     *
     * @since 1.0.0
     */
    function modify_product_admin_columns($columns)
    {

        $columns['product_image'] = __('Zdjęcie produktu', 'wp-olx');
        $columns['olx_status'] = __('Status na OLX', 'wp-olx');
        $columns['olx_add_advert'] = __('OLX', 'wp-olx');

        return $columns;
    }

    /**
     * Additional column content in CPT screen
     *
     * @since 1.0.0
     */
    function product_custom_column($column, $post_id)
    {

        $olx = new Olx();
        $advert = $olx->get_advert_by_post_id($post_id);

        switch ($column) {
                // product image column
            case 'product_image':
                $product_gallery = get_field('product_gallery', $post_id);
                $product_img = null;
                if ($product_gallery) {
                    $product_img = $product_gallery[0]['sizes']['medium'];
                    echo "<img src='$product_img' style='max-width: 120px; height: auto;' />";
                }
                break;
                // status column
            case 'olx_status':
                if ($advert) {
                    switch ($advert->status) {
                        case 'new':
                        case 'active':
                            echo "<span style='background-color:green;color:white;padding:8px;display:block'>" . __('Aktywne', 'wp-olx') . "</span>";
                            break;
                        case 'limited':
                        case 'unconfirmed':
                        case 'unpaid':
                        case 'moderated':
                        case 'blocked':
                        case 'disabled':
                        case 'limited':
                        case 'removed_by_moderator':
                            echo "<span style='background-color:red;color:white;padding:8px;display:block'>" . __('Problem', 'wp-olx') . "</span>";
                            break;
                        case 'removed_by_user':
                            echo "<span style='background-color:black;color:white;padding:8px;display:block'>" . __('Usunięte', 'wp-olx') . "</span>";
                            break;
                        case 'outdated':
                            echo "<span style='background-color:black;color:white;padding:8px;display:block'>" . __('Nieaktualne', 'wp-olx') . "</span>";
                            break;
                    }
                } else {
                    echo "<span style='background-color:grey;color:white;padding:8px;display:block'>" . __('Brak ogłoszenia', 'wp-olx') . "</span>";
                }
                break;
                // actions column
            case 'olx_add_advert':
                if ($advert) {
                    switch ($advert->status) {
                        case 'new':
                        case 'active':
                            echo "<a class='button button-secondary' href='$advert->url'>" . __('Zobacz w OLX', 'wp-olx') . "</a><br>";
                            break;
                        case 'limited':
                        case 'unconfirmed':
                        case 'unpaid':
                        case 'moderated':
                        case 'blocked':
                        case 'disabled':
                        case 'limited':
                        case 'removed_by_moderator':
                            echo "<br><a target='_blank' class='button button-secondary' href='https://www.olx.pl/mojolx/waiting/'>" . __('Zobacz status', 'wp-olx') . "</a>";
                            break;
                        case 'removed_by_user':
                            echo "<br><a target='_blank' class='button button-secondary' href='https://www.olx.pl/mojolx/waiting/'>" . __('Zobacz status', 'wp-olx') . "</a>";
                            break;
                        case 'outdated':
                            echo "<br><a target='_blank' class='button button-secondary' href='https://www.olx.pl/mojolx/waiting/'>" . __('Zobacz status', 'wp-olx') . "</a>";
                            break;
                    }
                } else {
                    echo "<button class='button button-secondary js-wp-olx-ajax-product' data-action-type='ajax_add_advert' data-product-id='$post_id'>" . __('Wystaw w OLX', 'wp-olx') . '</button>';
                }
                break;
        }
    }

    /**
     * @todo move cpt to plugin
     */
}
