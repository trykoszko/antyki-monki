<?php

/**
 * @wordpress-plugin
 * Plugin Name:       WP Antyki OLX
 * Plugin URI:        https://github.com/trykoszko/antyki-monki
 * Description:       OLX + Antyki
 * Version:           0.0.1
 * Author:            Michal Trykoszko
 * Author URI:        https://github.com/trykoszko
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       antyki
 * Domain Path:       /languages
 */

namespace Antyki;

use Antyki\Plugin\Main as Plugin;
use Antyki\Plugin\Activator as Activator;
use Antyki\Plugin\Deactivator as Deactivator;

use Antyki\Container\Main as DIContainer;

if (!defined('WPINC')) {
    die;
}

define('ANTYKI_VERSION', '1.0.0');

if (!defined('TEXTDOMAIN')) {
    define('TEXTDOMAIN', 'antyki');
}

define('ANTYKI_ROOT_DIR', plugin_dir_path(__FILE__));
define('ANTYKI_ROOT_URL', plugin_dir_url(__FILE__));

require_once plugin_dir_path(plugin_dir_path(plugin_dir_path(plugin_dir_path(__DIR__)))) . 'vendor/autoload.php';

function antyki_activate_plugin()
{
    Activator::activate();
}

function antyki_deactivate_plugin()
{
    Deactivator::deactivate();
}

function antyki_run_plugin()
{
    $diContainer = new DIContainer();
    $plugin = new Plugin($diContainer);
    $plugin->run();
}

register_activation_hook(__FILE__, 'Antyki\\antyki_activate_plugin');
register_deactivation_hook(__FILE__, 'Antyki\\antyki_deactivate_plugin');

antyki_run_plugin();
