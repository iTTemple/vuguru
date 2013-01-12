<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'db114781_vugurudb');

/** MySQL database username */
define('DB_USER', 'db114781_vuser');

/** MySQL database password */
define('DB_PASSWORD', '4Y3scW33FjA');

/** MySQL hostname */
define('DB_HOST', 'internal-db.s114781.gridserver.com');

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
define('AUTH_KEY',         '!Sa+bQ%aMv9/nDG_a-fUYc==2+E+R#8>iC86~Rbz%ByWBT56>hWIc?o]yIePUf$j');
define('SECURE_AUTH_KEY',  'mB-E=V^9+/]- B!(|;C+R*9VLg+;r>@^WpE)GvwYk3;l}6, .;9uS4aT=M/-4/mX');
define('LOGGED_IN_KEY',    'o~)GJdRe*9@Gx+$BO^L6Z=)h--j?yC}YiPY4{~Rit@5;snH AEC_8|Z6IzeM0V!?');
define('NONCE_KEY',        '%FkMM z-8T/J#DauL-~lUuY`m0W&%Cyg6LWQ+ ~+Z--? 6/!8@k+@X %QPUph]y-');
define('AUTH_SALT',        'L?n@!DmK$-}@;3;+4HrB8usDMMU9XV8=okc0cr6F.vx-,+A#9%%VxU~`{911dKk{');
define('SECURE_AUTH_SALT', ')3]3-^~Xc=``jLZh7?Vb|:Mh(W+,se+c/aG>1stL?aMu(m8yO;!r|f^;s}XzVASr');
define('LOGGED_IN_SALT',   '<[6U|yHxxDE0GQ89*>-M>g1ob+Erk +^+pRAs)BiIp%<2?88rt-<J+jve8bK9O1C');
define('NONCE_SALT',       '|u`Y!)c+shHh nkc}PR?CoJA9|-zrC21-;j902j[w{4?JX#%zz<95RNdL1>Sh>![');

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

