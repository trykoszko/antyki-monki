<?php
ini_set('display_errors', 0);

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__FILE__)));
$dotenv->load();

define('WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content');
define('WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content');

define('WPLANG', '');

define('AUTOMATIC_UPDATER_DISABLED', true);

define('WP_LOCAL_DEV', $_ENV['WP_LOCAL_DEV']);

define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

$table_prefix = $_ENV['DB_TABLE_PREFIX'];

define('WP_HOME', $_ENV['WP_HOME']);
define('WP_SITEURL', $_ENV['WP_SITEURL']);

define('WP_DEBUG', $_ENV['WP_DEBUG']);
define('WP_DEBUG_DISPLAY', $_ENV['WP_DEBUG_DISPLAY']);
define('WP_DEBUG_LOG', $_ENV['WP_DEBUG_LOG']);

define('AUTH_KEY', $_ENV['AUTH_KEY']);
define('SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY']);
define('LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY']);
define('NONCE_KEY', $_ENV['NONCE_KEY']);
define('AUTH_SALT', $_ENV['AUTH_SALT']);
define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT']);
define('LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT']);
define('NONCE_SALT', $_ENV['NONCE_SALT']);

define('ANTYKI_OLX_CLIENT_ID', $_ENV['ANTYKI_OLX_CLIENT_ID']);
define('ANTYKI_OLX_CLIENT_SECRET', $_ENV['ANTYKI_OLX_CLIENT_SECRET']);
define('ANTYKI_OLX_STATE', $_ENV['ANTYKI_OLX_STATE']);

define('GRAPHQL_DEBUG', true);

if ( ! defined( 'ABSPATH' ) ) {
    define('ABSPATH', dirname( __FILE__ ) . '/wp/');
}
require_once( ABSPATH . 'wp-settings.php');
