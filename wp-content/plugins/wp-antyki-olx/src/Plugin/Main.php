<?php

namespace Antyki\Plugin;

use Antyki\Olx\Ajax as Ajax;
use Antyki\Olx\Cache as Cache;

use Antyki\Plugin\Admin\Views as AdminViews;

/**
 * Main plugin class
 */
class Main
{

    public $twig;
    public $ajax;
    public $cache;
    public $adminViews;

    public function run()
    {
        $this->ajax = new Ajax();
        $this->cache = new Cache();
        $this->twig = new Twig();

        $this->adminViews = new AdminViews($this->twig);

        $this->init_hooks();
    }


    public function init_hooks()
    {
        \add_action('plugins_loaded', [$this, 'load_plugin_textdomain']);

        \add_action('admin_menu', [$this, 'add_to_admin_menu']);
    }

    public function load_plugin_textdomain()
    {
        \load_plugin_textdomain(
            TEXTDOMAIN,
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    public function add_to_admin_menu()
    {
        global $menu;
        \add_menu_page(
            __('Antyki - OLX', TEXTDOMAIN),
            __('Antyki - OLX', TEXTDOMAIN),
            'manage_options',
            'wp-antyki-olx',
            [$this->adminViews, 'authPage'],
            'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs/><path d="M343.815 60l14.999-60H153.186l14.999 60zM119.538 452l33.241 60h206.442l33.241-60zM58.281 340c1.985 4.519 4.183 8.983 6.614 13.371L102.918 422h306.165l38.022-68.629c2.431-4.388 4.629-8.851 6.614-13.371zM463.481 310c3.533-16.272 4.406-32.901 2.641-49.333-8.223 1.239-13.425 4.077-19.697 7.5-9.661 5.272-21.684 11.833-43.836 11.833-22.151 0-34.174-6.561-43.835-11.833-8.362-4.563-14.966-8.167-29.465-8.167-14.498 0-21.102 3.604-29.463 8.167-9.661 5.272-21.684 11.833-43.835 11.833-22.15 0-34.173-6.561-43.833-11.833-8.361-4.563-14.965-8.167-29.462-8.167-14.496 0-21.1 3.604-29.46 8.167-9.66 5.272-21.682 11.833-43.832 11.833s-34.173-6.561-43.833-11.833c-6.272-3.423-11.473-6.261-19.694-7.5-1.765 16.432-.892 33.061 2.641 49.333zM79.943 241.833c8.361 4.563 14.965 8.167 29.462 8.167 14.496 0 21.099-3.604 29.459-8.167 9.66-5.272 21.682-11.833 43.832-11.833s34.173 6.561 43.833 11.833c8.361 4.563 14.965 8.167 29.462 8.167 14.498 0 21.102-3.604 29.463-8.167 9.661-5.272 21.683-11.833 43.834-11.833 22.152 0 34.175 6.561 43.836 11.833 8.361 4.563 14.966 8.167 29.464 8.167 14.499 0 21.104-3.604 29.465-8.167 7.009-3.825 15.392-8.379 27.988-10.551-3.607-11.647-8.602-23.015-15.006-33.886-25.309-42.956-67.966-70.549-116.503-76.27L336.315 90h-160.63l7.781 31.126c-48.537 5.721-91.194 33.315-116.503 76.27-6.405 10.871-11.4 22.239-15.007 33.886 12.596 2.172 20.978 6.726 27.987 10.551z"/></svg>'),
            200
        );
        // add separator
        $menu[201] = [
            '',
            'read',
            '',
            '',
            'wp-menu-separator'
        ];
    }
}
