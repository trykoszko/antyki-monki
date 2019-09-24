<?php
/**
 * Plugin Name:       Integracja z OLX
 * Plugin URI:        github.com/trykoszko
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Trykoszko
 * Author URI:        github.com/trykoszko
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       olx
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

require plugin_dir_path( __FILE__ ) . 'includes/class-olx.php';

function run_olx() {

	$olx = new Olx();

}
run_olx();
