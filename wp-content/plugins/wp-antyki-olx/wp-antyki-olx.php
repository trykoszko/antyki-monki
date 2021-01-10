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

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('ANTYKI_VERSION', '0.0.1');
if (!defined('TEXTDOMAIN')) {
    define('TEXTDOMAIN', 'antyki');
}
define('ANTYKI_ROOT_DIR', plugin_dir_path(__FILE__));

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

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
    $plugin = new Plugin();
    $plugin->run();
}

register_activation_hook(__FILE__, 'Antyki\\antyki_activate_plugin');
register_deactivation_hook(__FILE__, 'Antyki\\antyki_deactivate_plugin');

antyki_run_plugin();
