<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
function get_config_consts($build){

    if($build == 'development'){
	  $settings = array('executive_wp_db', 'root', '5es6rx6no8322zs', 'localhost');
	} 
elseif($build == 'testing'){
	  $settings = array('bestprp0_executive_wp_db', 'bestprp0_isma153', '5es6rx6no8322zs', 'localhost');
	}	
elseif($build == 'production'){
	  $settings = array('', '', '', '');
	} 
	
	
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $settings[0]);

/** MySQL database username */
define('DB_USER', $settings[1]);

/** MySQL database password */
define('DB_PASSWORD', $settings[2]);

/** MySQL hostname */
define('DB_HOST', $settings[3]);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
}

get_config_consts('development');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '{4|qK|0{~+is|Jb#* +<bw`vVNR:&g&v/G7X7{=gYr&j+%_/q1E*53t )YHAAg|&');
define('SECURE_AUTH_KEY',  '(9tM(wu3:Rq:c:dAEmt]>a|;]}4kmdoStxG|M+j.WhGh)_AxR1c#-Db)i(jyNw9V');
define('LOGGED_IN_KEY',    'UWKw&,yS$LM|PQ_G<G)U,6rgav%0!x-dziA6D+pXCF&i9d|(0/:4Pwtc0Lm8ZbKg');
define('NONCE_KEY',        'TJ`czYdtpp>M(8n^i>_nr$|kaAA[x+uksOh]z|#=]Bd&-n-mcnE]}-q{lRf{]&1i');
define('AUTH_SALT',        'XoffIS-,ga_l!$cjG,;zK)YIFxfNK~86o;SWD$?A!Dn65rdHGL-nvNj$ISy+u}*}');
define('SECURE_AUTH_SALT', ' X._m[)_-pB{nd*|%h8-UqTqM,T^]:f+}=zN~a}N/%H{oLuXpq 8),z;D<IDHVYY');
define('LOGGED_IN_SALT',   '_ukv^eH.L|fcX]VgO=nR_Eg/*|JR2M$YI-X,Pm-SRN[Ce$ZB$q)NAx$+_6h$cu8{');
define('NONCE_SALT',       'd~V=aH5W?Q@.-ZrhC?Jc-ui,J|[`=f4|Ps&}<5wZy]N*68|g>tXVblS*JZzb$C-F');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_uz62oieu98y4s_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */



/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
