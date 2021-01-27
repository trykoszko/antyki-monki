<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

define('DB_NAME', 'antyki_monki_pl');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('WP_HOME', 'https://antyki.sors.smarthost.pl.devlocal');
define('WP_SITEURL', 'https://antyki.sors.smarthost.pl.devlocal/wp');

define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('WP_DEBUG_LOG', true);

$table_prefix = 'wp_';

define('AUTH_KEY',         '9b7455670e217c8f30c9e285b8f616333f4a8504');
define('SECURE_AUTH_KEY',  '40ed4a620e97c2d5103c5dd80036676fd9303bd6');
define('LOGGED_IN_KEY',    '2540a8e2253b2876f0a8b22ef47ce075b96f14ab');
define('NONCE_KEY',        '937df7673cfc9c40b3ae9a81f1972646d19be342');
define('AUTH_SALT',        '51d73c297c403f399967a395314c0412ea09497d');
define('SECURE_AUTH_SALT', '0616784e1e978cbb9f83d8716ae664c1626cc65d');
define('LOGGED_IN_SALT',   '7a711634c0781e387c53c6729b0702b84a639ea8');
define('NONCE_SALT',       '3f4231f7205812ba15bfdf97bcdfa165bed4cb74');

define('ANTYKI_OLX_CLIENT_ID', '100394');
define('ANTYKI_OLX_CLIENT_SECRET', 'c8FwEJTcroxhh2ohKqXSJQI9i9eB0pXcyCIdmCpO1W5iYuzJ');
define('ANTYKI_OLX_STATE', 'x93ld3v');

/**#@-*/

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
