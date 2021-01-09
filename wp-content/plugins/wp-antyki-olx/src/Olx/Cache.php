<?php

namespace Antyki\Olx;

/**
 * OLX cache class
 *
 */

/**
 * Class with cache to transients methods
 *
 * @since 1.0.0
 */
class Cache
{

    /**
     * Get transient from database
     *
     * @since 1.0.0
     *
     * @param   string  $transient_name  Transient name
     */
    public static function get($transient_name)
    {

        // only if cache is enabled
        if (\get_option('_olx_enable_cache') == 'on') {

            return \get_transient($transient_name);
        } else {

            return false;
        }
    }

    /**
     * Set transient in database
     *
     * @since 1.0.0
     *
     * @param   string  $transient_name  Transient name
     * @param   string  $value  Transient value
     * @param   string  $exp_date  Transient expiration date
     */
    public static function set($transient_name, $value, $exp_date = 60 * 60 * 24)
    {

        // only if cache is enabled
        if (\get_option('_olx_enable_cache') == 'on') {

            if (\set_transient($transient_name, $value, $exp_date)) {
                return true;
            } else {
                echo __('Error adding API response to cache', TEXTDOMAIN);
                return false;
            }
        } else {

            return true;
        }
    }

    /**
     * Remove all Antyki-related transients from database
     *
     * @since 1.0.0
     */
    public static function clear()
    {

        global $wpdb;
        $wpdb->query("DELETE FROM `wp_options` WHERE `option_name` LIKE ('%\_transient_antyki\_%')");
        $wpdb->query("DELETE FROM `wp_options` WHERE `option_name` LIKE ('%\_transient_timeout_antyki\_%')");
    }
}
