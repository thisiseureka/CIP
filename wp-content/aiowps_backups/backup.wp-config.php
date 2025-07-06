<?php
// Begin AIOWPSEC Firewall
if (file_exists('/home/brela/public_html/dev93.xyz/cip/aios-bootstrap.php')) {
	include_once('/home/brela/public_html/dev93.xyz/cip/aios-bootstrap.php');
}
// End AIOWPSEC Firewall

//Begin Really Simple Security session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple Security cookie settings
//Begin Really Simple Security key
define('RSSSL_KEY', 'S06xHHM9gPkEGuWHTu6G3Ihx0ap2s0bMMQ5xCt9zY3KX9JA3J1aikJf0UJJJxJQy');
//END Really Simple Security key

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'brela_wp590' );

/** Database username */
define( 'DB_USER', 'brela_wp590' );

/** Database password */
define( 'DB_PASSWORD', ')767(zp5SC' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ogtp5hbqtk7hfqtbis8reet1iytcgyi2x9uhilzkfba0iiuuunktefs621weqko3' );
define( 'SECURE_AUTH_KEY',  'drm1sedzrchbnvx41o5f5owwed5tfouda8jbs5ciynjwi1qmzbf6rsjwigdzvt3j' );
define( 'LOGGED_IN_KEY',    'zitem4c7zbincfpkmk5c962rl5wnuby9arw8ltq8sfzcwm7d1bz6zeijs43to1xo' );
define( 'NONCE_KEY',        'dd3qk3ckg9ord1ne2w5r77xmktlh82v2nbxdf0qlzkj9tmrymkvskw9avamultiu' );
define( 'AUTH_SALT',        'r9gloy6wvayjfrl0btjjz8z5ocnr48amvjfgv6wgozxw0bq29swf9ybofpntmnm1' );
define( 'SECURE_AUTH_SALT', 'pzssh5jsj5wbgcgzyheu8os9pd2p8lvzfaum4mei9yjullapjlhiwjhutsgpbc99' );
define( 'LOGGED_IN_SALT',   'be4fsqriknpym0h5g1wrpafncr2gvmcvqdswys8kmifrhmc2g4glhvpthcbnuxsv' );
define( 'NONCE_SALT',       '1qexdfmeerbrfala5rfhfpzlcjadxjeh8asbx5iouvlknwsoyn36ugrto8bfhwfq' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wplu_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
//define( 'WP_DEBUG', false );

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
define('SAVEQUERIES', true); // captures DB queries

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';