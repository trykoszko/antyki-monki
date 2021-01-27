<?php
ini_set( 'display_errors', 0 );

define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content' );
define( 'WPLANG', '' );

define( 'AUTOMATIC_UPDATER_DISABLED', true );

define( 'WP_LOCAL_DEV', getenv('WP_LOCAL_DEV') );

define( 'DB_NAME', getenv('DB_NAME') );
define( 'DB_USER', getenv('DB_USER') );
define( 'DB_PASSWORD', getenv('DB_PASSWORD') );
define( 'DB_HOST', getenv('DB_HOST') );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

$table_prefix = getenv('DB_TABLE_PREFIX');

define( 'WP_HOME', getenv('WP_HOME') );
define( 'WP_SITEURL', getenv('WP_SITEURL') );

define( 'WP_DEBUG', getenv('WP_DEBUG') );
define( 'WP_DEBUG_DISPLAY', getenv('WP_DEBUG_DISPLAY') );
define( 'WP_DEBUG_LOG', getenv('WP_DEBUG_LOG') );

define('AUTH_KEY', getenv('AUTH_KEY'));
define('SECURE_AUTH_KEY', getenv('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', getenv('LOGGED_IN_KEY'));
define('NONCE_KEY', getenv('NONCE_KEY'));
define('AUTH_SALT', getenv('AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', getenv('LOGGED_IN_SALT'));
define('NONCE_SALT', getenv('NONCE_SALT'));

define('ANTYKI_OLX_CLIENT_ID', getenv('ANTYKI_OLX_CLIENT_ID'));
define('ANTYKI_OLX_CLIENT_SECRET', getenv('ANTYKI_OLX_CLIENT_SECRET'));
define('ANTYKI_OLX_STATE', getenv('ANTYKI_OLX_STATE'));

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
}
require_once( ABSPATH . 'wp-settings.php' );
