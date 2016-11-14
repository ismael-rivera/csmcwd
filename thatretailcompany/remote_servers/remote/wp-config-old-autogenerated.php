<?php
/** 
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'WordPress_3_4_1_1_0_thatretailcompany_com_au');

/** MySQL database username */
define('DB_USER', 'thth324481014');

/** MySQL database password */
define('DB_PASSWORD', 'QJlnehPD');

/** MySQL hostname */
define('DB_HOST', 'mysql-2.netregistry.net');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'l*Vzjcg5PuUs#7YMMIb^QywFFRfhrh4SO6xysza7eHI&Br3LgsXOyq8&6P6luG0U');
define('SECURE_AUTH_KEY',  'RXTeXNk1&jDQjUYwrEIPxnISscOMj!7@$&cRY0ksixuRsoa3n*cU0)t@UvI#PZ&P');
define('LOGGED_IN_KEY',    ')wkYiqFpkQ!MLjmVaGU!bkv2YQPv8X(qstozEQ@@K&4)ieTnd9Pu^9lQ#jFrU$ap');
define('NONCE_KEY',        '6JdhNJP6O7^Lw0D97uylZUmXhLrf9!NwY3clT!lO)#Iv$nl7mOdYmkNp9$93&^lX');
define('AUTH_SALT',        '(u2#TR5uXIkcZ%K)^9nE&vHSRe6BJSsScZo*GD!P8bFvTFqiI0KaElcE$FDT@Z2%');
define('SECURE_AUTH_SALT', 'OVcjwdoEbQrXZ!F#pSyb5HV(Wh786G@zBSc(A6d9hCBdo5rQk)7e9idDHbLsKNuV');
define('LOGGED_IN_SALT',   'pXXhtbIJAjESS(RadlYdX%!gItt9tgRBUGDrU1eJ^&ROdTaLCHBU30w4Xzdh9jK%');
define('NONCE_SALT',       '4NqyJIUoLeUyI3!Lw$Eo*HyHbZAfp%Sc)qlj&n^lm1pg8UaEk)thHrHZBU8%jVx$');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'en_US');

define ('FS_METHOD', 'direct');

define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

?>
