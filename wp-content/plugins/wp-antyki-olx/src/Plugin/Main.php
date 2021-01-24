<?php

namespace Antyki\Plugin;

use Antyki\Container\Main as DIContainer;

/**
 * Main plugin class
 */
class Main
{
    public $container;
    public $isAuthenticated;
    public $adminViews;

    public function __construct(DIContainer $container)
    {
        $this->container = $container->getInstance();
        $this->isAuthenticated = $this->container->call(function ($olxInstance) {
            return $olxInstance->isAuthenticated;
        }, [
            'olxInstance' => $this->container->get('Olx')
        ]);
        $this->adminViews = $this->container->get('AdminViews');
    }

    public function run()
    {
        if (!defined('ANTYKI_ADMIN_MENU_SLUG')) {
            define('ANTYKI_ADMIN_MENU_SLUG', 'wp-antyki-olx');
        }

        define('ANTYKI_NONCE_NAME', 'wp-antyki-nonce');

        $this->initHooks();
    }


    public function initHooks()
    {
        \add_action('plugins_loaded', [$this, 'loadTextdomain']);

        \add_action('admin_menu', [$this, 'addToAdminMenu']);

        \add_action('antyki_cron_hook', [$this, 'bindCronActions']);

        \add_action('admin_bar_menu', [$this, 'addOlxStatusToAdminBar'], 100);

        \add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);

        \add_action('admin_init', [$this, 'registerCustomSettings']);
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
            'security' => \wp_create_nonce(ANTYKI_NONCE_NAME)
        ]);
        wp_enqueue_script('olxAdmin');

        wp_enqueue_style(
            'olxAdmin',
            ANTYKI_ROOT_URL . 'style/style.css',
            [],
            '1.0.0'
        );
    }

    public function bindCronActions()
    {
        // handle all cron actions here
    }

    function addOlxStatusToAdminBar($admin_bar)
    {
        $admin_bar->add_node([
            'id'    => 'olx-status',
            'title' => 'Status OLX',
            'href'  => '#'
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
        global $menu;

        if ($this->isAuthenticated) {

            add_menu_page(
                __('Antyki - OLX', TEXTDOMAIN),
                __('Dashboard', TEXTDOMAIN),
                'manage_options',
                ANTYKI_ADMIN_MENU_SLUG,
                [$this->adminViews, 'dashboardPage'],
                'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs/><path d="M343.815 60l14.999-60H153.186l14.999 60zM119.538 452l33.241 60h206.442l33.241-60zM58.281 340c1.985 4.519 4.183 8.983 6.614 13.371L102.918 422h306.165l38.022-68.629c2.431-4.388 4.629-8.851 6.614-13.371zM463.481 310c3.533-16.272 4.406-32.901 2.641-49.333-8.223 1.239-13.425 4.077-19.697 7.5-9.661 5.272-21.684 11.833-43.836 11.833-22.151 0-34.174-6.561-43.835-11.833-8.362-4.563-14.966-8.167-29.465-8.167-14.498 0-21.102 3.604-29.463 8.167-9.661 5.272-21.684 11.833-43.835 11.833-22.15 0-34.173-6.561-43.833-11.833-8.361-4.563-14.965-8.167-29.462-8.167-14.496 0-21.1 3.604-29.46 8.167-9.66 5.272-21.682 11.833-43.832 11.833s-34.173-6.561-43.833-11.833c-6.272-3.423-11.473-6.261-19.694-7.5-1.765 16.432-.892 33.061 2.641 49.333zM79.943 241.833c8.361 4.563 14.965 8.167 29.462 8.167 14.496 0 21.099-3.604 29.459-8.167 9.66-5.272 21.682-11.833 43.832-11.833s34.173 6.561 43.833 11.833c8.361 4.563 14.965 8.167 29.462 8.167 14.498 0 21.102-3.604 29.463-8.167 9.661-5.272 21.683-11.833 43.834-11.833 22.152 0 34.175 6.561 43.836 11.833 8.361 4.563 14.966 8.167 29.464 8.167 14.499 0 21.104-3.604 29.465-8.167 7.009-3.825 15.392-8.379 27.988-10.551-3.607-11.647-8.602-23.015-15.006-33.886-25.309-42.956-67.966-70.549-116.503-76.27L336.315 90h-160.63l7.781 31.126c-48.537 5.721-91.194 33.315-116.503 76.27-6.405 10.871-11.4 22.239-15.007 33.886 12.596 2.172 20.978 6.726 27.987 10.551z"/></svg>'),
                200
            );

            add_submenu_page(
                ANTYKI_ADMIN_MENU_SLUG,
                __('Antyki - Ustawienia', TEXTDOMAIN),
                __('Ustawienia', TEXTDOMAIN),
                'manage_options',
                ANTYKI_ADMIN_MENU_SLUG . '_settings',
                [$this->adminViews, 'settingsPage']
            );
        } else {

            add_menu_page(
                __('Antyki - OLX - x', TEXTDOMAIN),
                __('Antyki - OLX - x', TEXTDOMAIN),
                'manage_options',
                ANTYKI_ADMIN_MENU_SLUG,
                [$this->adminViews, 'authPage'],
                'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs/><path d="M343.815 60l14.999-60H153.186l14.999 60zM119.538 452l33.241 60h206.442l33.241-60zM58.281 340c1.985 4.519 4.183 8.983 6.614 13.371L102.918 422h306.165l38.022-68.629c2.431-4.388 4.629-8.851 6.614-13.371zM463.481 310c3.533-16.272 4.406-32.901 2.641-49.333-8.223 1.239-13.425 4.077-19.697 7.5-9.661 5.272-21.684 11.833-43.836 11.833-22.151 0-34.174-6.561-43.835-11.833-8.362-4.563-14.966-8.167-29.465-8.167-14.498 0-21.102 3.604-29.463 8.167-9.661 5.272-21.684 11.833-43.835 11.833-22.15 0-34.173-6.561-43.833-11.833-8.361-4.563-14.965-8.167-29.462-8.167-14.496 0-21.1 3.604-29.46 8.167-9.66 5.272-21.682 11.833-43.832 11.833s-34.173-6.561-43.833-11.833c-6.272-3.423-11.473-6.261-19.694-7.5-1.765 16.432-.892 33.061 2.641 49.333zM79.943 241.833c8.361 4.563 14.965 8.167 29.462 8.167 14.496 0 21.099-3.604 29.459-8.167 9.66-5.272 21.682-11.833 43.832-11.833s34.173 6.561 43.833 11.833c8.361 4.563 14.965 8.167 29.462 8.167 14.498 0 21.102-3.604 29.463-8.167 9.661-5.272 21.683-11.833 43.834-11.833 22.152 0 34.175 6.561 43.836 11.833 8.361 4.563 14.966 8.167 29.464 8.167 14.499 0 21.104-3.604 29.465-8.167 7.009-3.825 15.392-8.379 27.988-10.551-3.607-11.647-8.602-23.015-15.006-33.886-25.309-42.956-67.966-70.549-116.503-76.27L336.315 90h-160.63l7.781 31.126c-48.537 5.721-91.194 33.315-116.503 76.27-6.405 10.871-11.4 22.239-15.007 33.886 12.596 2.172 20.978 6.726 27.987 10.551z"/></svg>'),
                200
            );
        }

        // add separator
        $menu[201] = [
            '',
            'read',
            '',
            '',
            'wp-menu-separator'
        ];
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
}
