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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'bestprp0_trc_wp_db');

/** MySQL database username */
define('DB_USER', 'bestprp0_isma153');

/** MySQL database password */
define('DB_PASSWORD', '5es6rx6no8322zs');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'xqq,0@s-(t=kjv)SA~;*G fn$Ld+9Zt8t9#+-9Y?NovplW+QaI,9St 4 &7P|DT>');
define('SECURE_AUTH_KEY',  'S%);9P5(XHF#H7p1ilH#7^.rVE q~1xu,@cjJ |k?)(#mB|O9g/G4~^pO_uyxR`6');
define('LOGGED_IN_KEY',    '&eQIRs;hc=K4:uiN^!8w-WIUR D0CDh/ej3oyMl-Do@eT!`K)?2->LNz8L;B8i2n');
define('NONCE_KEY',        '(JJ(h|TVC{;8%m_<iX=hke 1*c-yRtAIcT|be^nrHZ.00@R@c{`*1:bt>YwADIK[');
define('AUTH_SALT',        '=B)#0EoGgmJUv:Dq+$,3Wd([p}<|g-]2$Uo0U-237%B]6!rG)eRJ(ag=|;X~x*RO');
define('SECURE_AUTH_SALT', 'ub0iL!+89H<}$@v^KV$0;%&:+z|q-v Hv4G6}>+E]e>Sj+|936[WZ#s74xj:I3@{');
define('LOGGED_IN_SALT',   'TT+[Zf(%&?D3|7N0+W4 yo:a_8Z+VsXtZf^S43g^pIpZ Ib!tNV%DBq)/,,FMwJ!');
define('NONCE_SALT',       '88oD|5LLu^JFz:<zYQDw+}}Qq:g|riLBu4KJGL.3),fdk(Ay-N?c{vN9+mQznk53');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_thatretailco89598_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
