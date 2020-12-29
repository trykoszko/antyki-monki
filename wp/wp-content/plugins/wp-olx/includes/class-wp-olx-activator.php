<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/trykoszko
 * @since      1.0.0
 *
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Olx
 * @subpackage Wp_Olx/includes
 * @author     Michal Trykoszko <trykoszkom@gmail.com>
 */
class Wp_Olx_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
    public static function activate() {

        // CRON
        if ( ! wp_next_scheduled( 'wp_olx_cron_hook' ) ) {
			wp_schedule_event( time(), 'daily', 'wp_olx_cron_hook' );
		}

	}

}
