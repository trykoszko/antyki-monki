<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 * @author     Michal Trykoszko <trykoszkom@gmail.com>
 */
class Wp_Olx_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

        // CRON
		$timestamp = wp_next_scheduled( 'wp_olx_cron_hook' );
		wp_unschedule_event( $timestamp, 'wp_olx_cron_hook' );

	}

}
